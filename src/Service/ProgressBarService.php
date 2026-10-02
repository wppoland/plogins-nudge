<?php

declare(strict_types=1);

namespace Nudge\Service;

defined('ABSPATH') || exit;

use Nudge\Contract\HasHooks;

/**
 * Renders the free-shipping progress bar on the cart and checkout.
 *
 * The bar is computed server-side and re-rendered by WooCommerce whenever the
 * cart totals update (it lives inside the cart/checkout totals fragment). A
 * tiny, dependency-free script listens for WooCommerce's `updated_cart_totals`
 * and `updated_checkout` events to animate the width smoothly between renders.
 *
 * Renders on the classic cart and checkout templates. The Cart and Checkout
 * Blocks fire none of these hooks, so a store built on the blocks gets no bar,
 * and the settings screen says so next to the placement checkboxes.
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

    public function registerHooks(): void
    {
        $settings = $this->settings();

        if (empty($settings['enabled'])) {
            return;
        }

        add_action('wp_enqueue_scripts', [$this, 'registerAssets']);

        // One bar per page. There used to be a second, "inline" bar hooked
        // inside the totals table on both pages, so every shopper saw the bar
        // twice. Inside a <tfoot> the browser also hoists the <div> out of the
        // table, so each checkout refresh left one more copy behind.
        //
        // Classic template hooks only: the Cart and Checkout blocks fire none of
        // them, and the settings screen warns when a page uses the block.
        if (! empty($settings['show_on_cart'])) {
            // Inside div.cart_totals, which WooCommerce re-renders on update.
            add_action('woocommerce_before_cart_totals', [$this, 'renderCartBar']);
        }

        if (! empty($settings['show_on_checkout'])) {
            add_action('woocommerce_before_checkout_form', [$this, 'renderCheckoutBar'], 5);
            // The checkout form is not re-rendered on update, so send the bar
            // as a fragment to keep it current when a coupon changes the total.
            add_filter('woocommerce_update_order_review_fragments', [$this, 'checkoutFragment']);
        }
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
        echo $this->kses($this->buildBar('checkout')); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered by kses().
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
        $fragments['div.nudge--checkout'] = $this->kses($this->buildBar('checkout'));

        return $fragments;
    }

    /**
     * Build the bar markup for a given context, or an empty string when there
     * is nothing meaningful to show.
     */
    private function buildBar(string $context): string
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

        $this->enqueueAssets();

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
        do_action('nudge/bar_rendered', $barContext, $settings);

        ob_start();
        $this->renderTemplate('progress-bar', $barContext);

        return (string) ob_get_clean();
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
