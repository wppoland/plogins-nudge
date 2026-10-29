<?php

/**
 * Partner plugins shape the bar without being required.
 *
 * - Sojlo minimum order total, cart subtotal under it: the message says what is
 *   missing for the minimum and the bar is not shown as complete.
 * - At or over the minimum, Sojlo off, or Sojlo not booted: context untouched.
 * - A local pickup method is chosen (classic or Blocks id): the bar is hidden,
 *   and buildBar() prints nothing on every placement.
 *
 * Run: php tests/partner-context-check.php
 */

declare(strict_types=1);

namespace Minimum\Rules {
    final class Settings
    {
        public static bool $enabled = true;
        public static float $minimum = 100.0;

        public function is_enabled(): bool
        {
            return self::$enabled;
        }

        public function min_order_total(): float
        {
            return self::$minimum;
        }
    }
}

namespace {
    define('ABSPATH', __DIR__);
    define('NUDGE_DIR', dirname(__DIR__) . '/');
    define('NUDGE_URL', 'https://example.test/');

    class WC_Cart
    {
        public static float $subtotal = 60.0;

        public function is_empty(): bool
        {
            return false;
        }

        public function get_subtotal(): float
        {
            return self::$subtotal;
        }

        public function get_displayed_subtotal(): float
        {
            return self::$subtotal;
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

    class WC_Session_Stub
    {
        /** @var array<int, string> */
        public static array $chosen = ['flat_rate:1'];

        public function get(string $key): mixed
        {
            return 'chosen_shipping_methods' === $key ? self::$chosen : null;
        }
    }

    $GLOBALS['booted'] = true;

    function WC(): object
    {
        return (object) ['cart' => new WC_Cart(), 'session' => new WC_Session_Stub()];
    }

    function did_action(string $hook): int
    {
        return 'minimum/booted' === $hook && $GLOBALS['booted'] ? 1 : 0;
    }

    function get_option(string $name, mixed $default = false): mixed
    {
        return ['threshold_source' => 'manual', 'manual_threshold' => 50.0];
    }

    function apply_filters(string $hook, mixed $value, mixed ...$args): mixed
    {
        return 'nudge/bar_context' === $hook ? $GLOBALS['service']->partnerContext($value) : $value;
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
        return number_format($amount, 2);
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

    $service            = new \Nudge\Service\ProgressBarService(new \Nudge\Service\ThresholdResolver());
    $GLOBALS['service'] = $service;
    $base               = ['message' => 'free shipping copy', 'reached' => true];

    // 60 in the cart, Sojlo minimum 100: 40 missing, never shown as complete.
    $out = $service->partnerContext($base);
    $check('Add 40.00 more to reach the minimum order of 100.00.' === $out['message'], 'under the minimum the bar names the missing amount');
    $check(false === $out['reached'], 'under the minimum the bar is not complete');

    WC_Cart::$subtotal = 100.0;
    $check($base === $service->partnerContext($base), 'at the minimum the context is untouched');
    WC_Cart::$subtotal = 60.0;

    \Minimum\Rules\Settings::$enabled = false;
    $check($base === $service->partnerContext($base), 'Sojlo switched off leaves the context alone');
    \Minimum\Rules\Settings::$enabled = true;

    $GLOBALS['booted'] = false;
    $check($base === $service->partnerContext($base), 'Sojlo not booted leaves the context alone');
    $GLOBALS['booted'] = true;

    foreach (['local_pickup:3', 'pickup_location:0'] as $method) {
        WC_Session_Stub::$chosen = [$method];
        $check(! empty($service->partnerContext($base)['hidden']), "{$method} hides the bar");
        $check('' === $service->storeApiData()['cart'], "{$method} sends no bar to the blocks");
        $check('<div class="nudge-slot nudge-slot--checkout" data-nudge-slot="checkout"></div>' === $service->checkoutFragment([])['div.nudge-slot--checkout'], "{$method} keeps an empty checkout slot");
    }

    WC_Session_Stub::$chosen = ['flat_rate:1'];
    $check(str_contains($service->storeApiData()['cart'], 'minimum order'), 'flat rate shows the bar again, with the minimum message');

    echo 0 === $failures ? "OK: partners shape the bar, never required\n" : '';
    exit($failures > 0 ? 1 : 0);
}
