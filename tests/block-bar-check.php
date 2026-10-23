<?php

/**
 * The Cart and Checkout blocks get the same bar as the classic pages.
 *
 * - render_block prepends a slot only to woocommerce/cart and woocommerce/checkout,
 *   and only when that placement is ticked.
 * - The Store API payload carries the same markup the page printed, so the block
 *   script redraws exactly what the server would.
 * - An empty cart yields an empty slot and an empty payload, never a bar.
 * - [shiplume] prints nothing when the bar is off.
 *
 * Run: php tests/block-bar-check.php
 */

declare(strict_types=1);

namespace {
    define('ABSPATH', __DIR__);
    define('NUDGE_DIR', dirname(__DIR__) . '/');
    define('NUDGE_URL', 'https://example.test/');
    define('ARRAY_A', 'ARRAY_A');

    class WC_Cart
    {
        public static bool $empty = false;

        public function is_empty(): bool
        {
            return self::$empty;
        }

        public function get_displayed_subtotal(): float
        {
            return 30.0;
        }

        public function display_prices_including_tax(): bool
        {
            return false;
        }

        public function get_discount_tax(): float
        {
            return 0.0;
        }

        public function get_discount_total(): float
        {
            return 0.0;
        }
    }

    /** @var array<string, mixed> */
    $GLOBALS['nudge_option'] = [];

    function WC(): object
    {
        return (object) ['cart' => new WC_Cart()];
    }

    function get_option(string $name, mixed $default = false): mixed
    {
        return $GLOBALS['nudge_option'];
    }

    function apply_filters(string $hook, mixed $value, mixed ...$args): mixed
    {
        return $value;
    }

    function do_action(string $hook, mixed ...$args): void
    {
    }

    function __(string $text, string $domain): string
    {
        return $text;
    }

    function esc_attr(string $text): string
    {
        return htmlspecialchars($text, ENT_QUOTES);
    }

    function esc_attr_e(string $text, string $domain): void
    {
        echo esc_attr($text);
    }

    function sanitize_html_class(string $class): string
    {
        return preg_replace('/[^A-Za-z0-9_-]/', '', $class);
    }

    function wp_kses_post(string $html): string
    {
        return $html;
    }

    /** @return array<string, array<string, bool>> */
    function wp_kses_allowed_html(string $context): array
    {
        return [];
    }

    /** @param array<string, mixed> $allowed */
    function wp_kses(string $html, array $allowed): string
    {
        return $html;
    }

    function wc_price(float $amount): string
    {
        return '<span class="amount">' . number_format($amount, 2) . '</span>';
    }

    function wc_format_decimal(string $value): string
    {
        return $value;
    }

    function wc_get_price_decimals(): int
    {
        return 2;
    }

    function wp_style_is(string $handle, string $list): bool
    {
        return true;
    }

    function wp_enqueue_style(string $handle): void
    {
    }

    function wp_enqueue_script(string $handle): void
    {
    }

    require __DIR__ . '/../src/Contract/HasHooks.php';
    require __DIR__ . '/../src/Service/Texts.php';
    require __DIR__ . '/../src/Service/ThresholdResolver.php';
    require __DIR__ . '/../src/Service/ProgressBarService.php';

    $failures = 0;
    $check    = static function (bool $ok, string $what) use (&$failures): void {
        if (! $ok) {
            echo "FAIL: {$what}\n";
            $failures++;
        }
    };

    $service = new \Nudge\Service\ProgressBarService(new \Nudge\Service\ThresholdResolver());
    $block   = '<div class="wp-block-woocommerce-cart"></div>';

    // Manual goal of 50 with a 30 cart: 60% and 20.00 to go.
    $GLOBALS['nudge_option'] = ['threshold_source' => 'manual', 'manual_threshold' => 50.0];

    $cart = $service->renderBlock($block, ['blockName' => 'woocommerce/cart']);
    $check(str_ends_with($cart, $block), 'cart block content is kept after the bar');
    $check(str_starts_with($cart, '<div class="nudge-slot nudge-slot--cart" data-nudge-slot="cart">'), 'cart block gets a cart slot first');
    $check(str_contains($cart, 'aria-valuenow="60"') && str_contains($cart, '20.00'), 'cart block bar shows 60% and 20.00 remaining');

    $check($service->renderBlock($block, ['blockName' => 'woocommerce/mini-cart']) === $block, 'other blocks are untouched');
    $check($service->renderBlock($block, ['blockName' => null]) === $block, 'freeform content is untouched');

    $data = $service->storeApiData();
    $check(str_contains($cart, $data['cart']), 'Store API cart markup equals the printed bar');
    $check(str_contains($data['checkout'], 'nudge--checkout'), 'Store API checkout markup is the checkout bar');

    $GLOBALS['nudge_option']['show_on_checkout'] = false;
    $check($service->renderBlock($block, ['blockName' => 'woocommerce/checkout']) === $block, 'unticked checkout leaves the block alone');
    $check('' === $service->storeApiData()['checkout'], 'unticked checkout sends no checkout bar');

    WC_Cart::$empty = true;
    $check('' === $service->storeApiData()['cart'], 'empty cart sends no bar');
    $check(str_contains($service->renderBlock($block, ['blockName' => 'woocommerce/cart']), 'data-nudge-slot="cart"></div>'), 'empty cart keeps an empty slot');
    WC_Cart::$empty = false;

    $GLOBALS['nudge_option']['enabled'] = false;
    $check('' === $service->shortcode(), 'shortcode prints nothing when the bar is off');

    echo 0 === $failures ? "OK: blocks, Store API and shortcode share one bar\n" : '';
    exit($failures > 0 ? 1 : 0);
}
