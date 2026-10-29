<?php

declare(strict_types=1);

namespace Nudge\Service;

defined('ABSPATH') || exit;

use Nudge\Contract\HasHooks;

/**
 * Renders the free-shipping progress bar.
 *
 * Placements: the classic cart and checkout templates, the Cart and Checkout
 * blocks, the classic mini-cart widget and the [shiplume] shortcode. Every
 * placement draws the same server-built bar from barContext(), so the goal,
 * the coupon rule and the messages never differ between them.
 *
 * Classic pages: the bar lives inside markup WooCommerce re-renders (cart
 * totals, checkout fragments, mini-cart fragments), and a tiny script animates
 * the width between renders.
 *
 * Blocks: the blocks are React apps that replace their own inner markup, so the
 * bar is printed just before the block wrapper and kept current from the Store
 * API cart response, which carries the bar under extensions.shiplume.
 *
 * Robustness: when the feature is disabled, the cart is empty, or no
 * free-shipping threshold is configured, the bar is hidden rather than rendered
 * in a broken or always-complete state.
 */
final class ProgressBarService implements HasHooks
{
    private const OPTION = 'nudge_settings';

    private const ASSET_HANDLE = 'nudge';

    private ThresholdResolver $resolver;

    /** Guards against enqueueing the stylesheet more than once per request. */
    private bool $assetsEnqueued = false;

    public function __construct(ThresholdResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /** Store API extension namespace: extensions.shiplume in the cart response. */
    public const STORE_NAMESPACE = 'shiplume';

    /** Block name => bar context, for the Cart and Checkout blocks. */
    private const BLOCKS = [
        'woocommerce/cart'     => 'cart',
        'woocommerce/checkout' => 'checkout',
    ];

    public function registerHooks(): void
    {
        // Registered even when the bar is off, so a disabled bar prints nothing
        // instead of a literal "[shiplume]" in the page.
        add_shortcode('shiplume', [$this, 'shortcode']);

        $settings = $this->settings();

        if (empty($settings['enabled'])) {
            return;
        }

        add_action('wp_enqueue_scripts', [$this, 'registerAssets']);

        // After PRO tiered rewards (10): the order minimum blocks checkout, so
        // its message wins over any free-shipping copy.
        add_filter('nudge/bar_context', [$this, 'partnerContext'], 20);

        // One bar per page. There used to be a second, "inline" bar hooked
        // inside the totals table on both pages, so every shopper saw the bar
        // twice. Inside a <tfoot> the browser also hoists the <div> out of the
        // table, so each checkout refresh left one more copy behind.
        if (! empty($settings['show_on_cart'])) {
            // Inside div.cart_totals, which WooCommerce re-renders on update.
            add_action('woocommerce_before_cart_totals', [$this, 'renderCartBar']);
        }

        if (! empty($settings['show_on_checkout'])) {
            add_action('woocommerce_before_checkout_form', [$this, 'renderCheckoutBar'], 5);
            // The checkout form is not re-rendered on update, so send the bar
            // as a fragment to keep it current when a coupon or the shipping
            // method changes. The fragment targets the slot, which survives a
            // hidden bar, so the bar can come back.
            add_filter('woocommerce_update_order_review_fragments', [$this, 'checkoutFragment']);
        }

        if (! empty($settings['show_on_cart']) || ! empty($settings['show_on_checkout'])) {
            add_filter('render_block', [$this, 'renderBlock'], 10, 2);
            $this->registerStoreApiData();
        }

        if (! empty($settings['show_in_mini_cart'])) {
            // Inside div.widget_shopping_cart_content, a cart fragment, so the
            // bar is rebuilt whenever WooCommerce refreshes the mini-cart.
            add_action('woocommerce_widget_shopping_cart_before_buttons', [$this, 'renderMiniCartBar']);
            // The widget is usually filled by an AJAX fragment after page load,
            // when it is too late to enqueue the stylesheet.
            add_action('wp_enqueue_scripts', [$this, 'enqueueStyle'], 20);
        }

        // Keeps [shiplume] bars current after an AJAX add to cart.
        add_filter('woocommerce_add_to_cart_fragments', [$this, 'shortcodeFragment']);
    }

    /**
     * Register the stylesheet and the small progressive-enhancement script.
     * Only registered here; actually enqueued lazily when the bar renders, so a
     * page without a bar never loads the assets.
     */
    public function registerAssets(): void
    {
        wp_register_style(
            self::ASSET_HANDLE,
            NUDGE_URL . 'assets/css/nudge.css',
            [],
            \Nudge\VERSION,
        );

        wp_register_script(
            self::ASSET_HANDLE,
            NUDGE_URL . 'assets/js/nudge.js',
            [],
            \Nudge\VERSION,
            ['in_footer' => true, 'strategy' => 'defer'],
        );

        wp_register_script(
            self::ASSET_HANDLE . '-blocks',
            NUDGE_URL . 'assets/js/nudge-blocks.js',
            ['wp-data'],
            \Nudge\VERSION,
            ['in_footer' => true],
        );
    }

    public function enqueueStyle(): void
    {
        wp_enqueue_style(self::ASSET_HANDLE);
    }

    private function enqueueAssets(): void
    {
        if ($this->assetsEnqueued) {
            return;
        }

        // registerAssets() runs on wp_enqueue_scripts; if a render fires before
        // that (unlikely), register on demand so enqueue still succeeds.
        if (! wp_style_is(self::ASSET_HANDLE, 'registered')) {
            $this->registerAssets();
        }

        wp_enqueue_style(self::ASSET_HANDLE);
        wp_enqueue_script(self::ASSET_HANDLE);

        $this->assetsEnqueued = true;
    }

    /**
     * Render wrapper for the classic cart page (adds outer spacing class).
     */
    public function renderCartBar(): void
    {
        echo $this->kses($this->buildBar('cart')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by kses().
    }

    /**
     * Render wrapper for the checkout page.
     */
    public function renderCheckoutBar(): void
    {
        echo $this->slot('checkout'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- slot() escapes and filters by kses().
    }

    /**
     * Render wrapper for the classic mini-cart widget, above its buttons.
     */
    public function renderMiniCartBar(): void
    {
        echo $this->kses($this->buildBar('mini-cart')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by kses().
    }

    /**
     * [shiplume] prints the bar for the current cart, or nothing when the bar
     * is off or there is no goal to show.
     */
    public function shortcode(): string
    {
        if (empty($this->settings()['enabled'])) {
            return '';
        }

        // Enqueued even for an empty bar: an add-to-cart fragment may fill the
        // slot later, and it needs the stylesheet already on the page.
        $this->enqueueAssets();

        return $this->slot('shortcode');
    }

    /**
     * @param array<string, string> $fragments
     * @return array<string, string>
     */
    public function shortcodeFragment(array $fragments): array
    {
        $fragments['div.nudge-slot--shortcode'] = $this->slot('shortcode', false);

        return $fragments;
    }

    /**
     * Print the bar before the Cart or Checkout block wrapper. Inside the
     * wrapper React would replace it on the first render.
     *
     * @param array<string, mixed> $block
     */
    public function renderBlock(string $content, array $block): string
    {
        $context = self::BLOCKS[(string) ($block['blockName'] ?? '')] ?? '';

        if ('' === $context || empty($this->settings()['show_on_' . $context])) {
            return $content;
        }

        // The slot is printed even for an empty cart, so the script has a place
        // to draw the bar once the shopper adds something.
        $this->enqueueAssets();
        wp_enqueue_script(self::ASSET_HANDLE . '-blocks');

        return $this->slot($context) . $content;
    }

    /**
     * Bar data for the Cart and Checkout blocks, sent with every Store API
     * cart response so the block script can redraw the bar after a change.
     */
    private function registerStoreApiData(): void
    {
        if (! function_exists('woocommerce_store_api_register_endpoint_data')) {
            return;
        }

        woocommerce_store_api_register_endpoint_data([
            'endpoint'        => 'cart',
            'namespace'       => self::STORE_NAMESPACE,
            'data_callback'   => [$this, 'storeApiData'],
            'schema_callback' => [$this, 'storeApiSchema'],
            'schema_type'     => ARRAY_A,
        ]);
    }

    /**
     * The rendered bar per block placement, empty where nothing should show.
     *
     * @return array<string, string>
     */
    public function storeApiData(): array
    {
        $settings = $this->settings();
        $data     = [];

        foreach (self::BLOCKS as $context) {
            $data[$context] = empty($settings['show_on_' . $context]) ? '' : $this->kses($this->buildBar($context, false));
        }

        return $data;
    }

    /** @return array<string, array<string, mixed>> */
    public function storeApiSchema(): array
    {
        $schema = [];

        foreach (self::BLOCKS as $context) {
            $schema[$context] = [
                'description' => __('Free-shipping progress bar markup for this placement, empty when hidden.', 'shiplume'),
                'type'        => 'string',
                'context'     => ['view', 'edit'],
                'readonly'    => true,
            ];
        }

        return $schema;
    }

    /**
     * A wrapper that always prints, so a script or a cart fragment can fill it
     * later even when the bar is empty right now.
     */
    private function slot(string $context, bool $enqueue = true): string
    {
        return '<div class="nudge-slot nudge-slot--' . esc_attr($context) . '" data-nudge-slot="' . esc_attr($context) . '">'
            . $this->kses($this->buildBar($context, $enqueue))
            . '</div>';
    }

    /**
     * wp_kses_post() with the progressbar's ARIA attributes. The post allow-list
     * drops aria-valuenow/min/max and aria-atomic, so the bar reached screen
     * readers as a progressbar with no value.
     */
    private function kses(string $html): string
    {
        $allowed = wp_kses_allowed_html('post');

        $allowed['div'] = array_merge($allowed['div'] ?? [], [
            'aria-valuemin' => true,
            'aria-valuemax' => true,
            'aria-valuenow' => true,
        ]);
        $allowed['p'] = array_merge($allowed['p'] ?? [], ['aria-atomic' => true]);

        return wp_kses($html, $allowed);
    }

    /**
     * @param array<string, string> $fragments
     * @return array<string, string>
     */
    public function checkoutFragment(array $fragments): array
    {
        $fragments['div.nudge-slot--checkout'] = $this->slot('checkout', false);

        return $fragments;
    }

    /**
     * Build the bar markup for a given context, or an empty string when there
     * is nothing meaningful to show.
     */
    private function buildBar(string $context, bool $enqueue = true): string
    {
        // Cart must be available and not empty.
        if (! function_exists('WC') || ! WC()->cart instanceof \WC_Cart || WC()->cart->is_empty()) {
            return '';
        }

        $settings  = $this->settings();
        $threshold = $this->resolver->threshold($settings);

        // No configured free-shipping goal, hide rather than show a broken bar.
        if ($threshold <= 0.0) {
            return '';
        }

        $total     = $this->resolver->cartTotal();
        $remaining = max(0.0, $threshold - $total);
        $reached   = $remaining <= 0.0;
        $percent   = $threshold > 0.0 ? min(100, (int) round(($total / $threshold) * 100)) : 0;

        $remainingHtml = wc_price($remaining);

        // The token was only substituted in the progress copy, so a merchant who
        // followed the settings screen and put {amount} in the success message
        // shipped a literal "{amount}" to shoppers. Substitute in whichever
        // message we render; past the goal the remaining amount is zero, which
        // is exactly what the field's preview shows.
        $message = str_replace('{amount}', $remainingHtml, (string) ($reached
            ? ($settings['message_success'] ?? '')
            : ($settings['message_progress'] ?? '')));

        if ($enqueue) {
            $this->enqueueAssets();
        }

        /** @var array{context:string,percent:int,reached:bool,message:string,threshold:float,total:float,remaining:float,tier_markers?:int[]} $barContext */
        $barContext = apply_filters('nudge/bar_context', [
            'context'   => $context,
            'percent'   => $percent,
            'reached'   => $reached,
            'message'   => $message,
            'threshold' => $threshold,
            'total'     => $total,
            'remaining' => $remaining,
        ], $settings);

        /**
         * Fires after the bar context is resolved and before the template renders.
         *
         * @param array<string, mixed> $barContext Progress data for the bar.
         * @param array<string, mixed> $settings   Merged nudge settings.
         */
        // A listener (local pickup chosen, see partnerContext()) asked for no bar.
        if (! empty($barContext['hidden'])) {
            return '';
        }

        do_action('nudge/bar_rendered', $barContext, $settings);

        ob_start();
        $this->renderTemplate('progress-bar', $barContext);

        return (string) ob_get_clean();
    }

    /**
     * Partner plugins, detected and never required.
     *
     * - A local pickup method is chosen (WooCommerce core, Prenejo): shipping
     *   does not apply, so the bar is hidden.
     * - Sojlo is active with a minimum order total the cart is still under: the
     *   bar says what is missing for the minimum, the same subtotal Sojlo checks.
     *
     * @param array<string, mixed> $barContext
     * @return array<string, mixed>
     */
    public function partnerContext(array $barContext): array
    {
        if ($this->isLocalPickupChosen()) {
            $barContext['hidden'] = true;

            return $barContext;
        }

        $minimum = $this->sojloMinimum();

        if ($minimum <= 0.0) {
            return $barContext;
        }

        $subtotal = (float) WC()->cart->get_subtotal();

        if ($subtotal >= $minimum) {
            return $barContext;
        }

        $barContext['reached'] = false;
        $barContext['message'] = sprintf(
            /* translators: 1: amount still missing, 2: minimum order total. Both are formatted prices. */
            __('Add %1$s more to reach the minimum order of %2$s.', 'shiplume'),
            wc_price($minimum - $subtotal),
            wc_price($minimum),
        );

        return $barContext;
    }

    /**
     * The chosen shipping method is a local pickup one, as Prenejo detects it:
     * classic method ids start with local_pickup, the Blocks pickup with
     * pickup_location.
     */
    private function isLocalPickupChosen(): bool
    {
        if (! function_exists('WC') || null === WC()->session) {
            return false;
        }

        $chosen = WC()->session->get('chosen_shipping_methods');

        foreach (is_array($chosen) ? $chosen : [] as $method) {
            if (str_starts_with((string) $method, 'local_pickup') || str_starts_with((string) $method, 'pickup_location')) {
                return true;
            }
        }

        return false;
    }

    /** Sojlo's minimum order total, 0 when Sojlo is absent, off or has none. */
    private function sojloMinimum(): float
    {
        if (! did_action('minimum/booted') || ! class_exists('\Minimum\Rules\Settings')) {
            return 0.0;
        }

        $sojlo = new \Minimum\Rules\Settings();

        return $sojlo->is_enabled() ? $sojlo->min_order_total() : 0.0;
    }

    /**
     * @param array<string, mixed> $vars
     */
    private function renderTemplate(string $template, array $vars): void
    {
        $file = NUDGE_DIR . 'templates/' . $template . '.php';

        if (! is_readable($file)) {
            return;
        }

        // Not named $context: EXTR_SKIP kept the array under that name, so the
        // template printed "nudge--Array" and data-nudge-placement="Array".
        extract($vars, EXTR_SKIP);
        require $file;
    }

    /**
     * Stored settings merged over packaged defaults, with every empty
     * customer-facing message filled from the translated defaults.
     *
     * Texts::apply() runs here, on the way to the storefront, and nowhere near
     * a save, so no language is ever written into the option.
     *
     * @return array<string, mixed>
     */
    private function settings(): array
    {
        $stored = get_option(self::OPTION, []);

        if (! is_array($stored)) {
            $stored = [];
        }

        /** @var array<string, mixed> $defaults */
        $defaults = require NUDGE_DIR . 'config/defaults.php';

        return Texts::apply(array_merge($defaults, $stored));
    }
}
