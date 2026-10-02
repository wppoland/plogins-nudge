<?php

declare(strict_types=1);

namespace Nudge\Service;

defined('ABSPATH') || exit;

/**
 * Resolves the free-shipping threshold and the current progress towards it.
 *
 * In "auto" mode it inspects every shipping zone (plus the "rest of the world"
 * zone) for an enabled `free_shipping` method whose requirement involves a
 * minimum order amount, and uses the smallest such amount it finds. When no
 * qualifying method exists, or the source is set to "manual", it falls back to
 * the merchant-configured amount.
 *
 * All amounts are floats in the store currency. A threshold of 0 (or less) means
 * "no free-shipping goal is configured"; callers should hide the bar in that
 * case rather than render a broken or always-complete bar.
 */
final class ThresholdResolver
{
    /**
     * Whether the free-shipping method behind the last resolved threshold has
     * "Apply minimum order rule before coupon discount" ticked. WooCommerce then
     * compares the cart before discounts, so cartTotal() must too.
     *
     * ponytail: state between threshold() and cartTotal(); both are always
     * called together per render. Return a value object if a caller ever needs them apart.
     */
    private bool $ignoreDiscounts = false;

    /**
     * Compute the active free-shipping threshold for the given settings.
     *
     * @param array<string, mixed> $settings
     */
    public function threshold(array $settings): float
    {
        $this->ignoreDiscounts = false;

        $source = ($settings['threshold_source'] ?? 'auto') === 'manual' ? 'manual' : 'auto';
        $manual = max(0.0, (float) ($settings['manual_threshold'] ?? 0.0));

        if ($source === 'manual') {
            return (float) apply_filters('nudge/threshold', $manual, $settings);
        }

        $auto = $this->autoThreshold();

        // Fall back to the manual amount when no free-shipping method is found.
        $threshold = $auto > 0.0 ? $auto : $manual;

        return (float) apply_filters('nudge/threshold', $threshold, $settings);
    }

    /**
     * The smallest qualifying free-shipping minimum for a single shipping zone.
     */
    public function zoneThreshold(int $zoneId): float
    {
        if (! class_exists(\WC_Shipping_Zones::class)) {
            return 0.0;
        }

        $zone = $zoneId > 0
            ? \WC_Shipping_Zones::get_zone($zoneId)
            : \WC_Shipping_Zones::get_zone_by('zone_id', 0);

        if (! $zone instanceof \WC_Shipping_Zone) {
            return 0.0;
        }

        return $this->lowestMinimum($zone->get_shipping_methods(true));
    }

    /**
     * The smallest qualifying free-shipping minimum order amount across all
     * shipping zones, or 0.0 when none is configured.
     */
    private function autoThreshold(): float
    {
        if (! class_exists(\WC_Shipping_Zones::class)) {
            return 0.0;
        }

        $best = 0.0;

        // Configured zones.
        foreach (\WC_Shipping_Zones::get_zones() as $zone) {
            $best = $this->lowestMinimum((array) ($zone['shipping_methods'] ?? []), $best);
        }

        // "Rest of the world" zone (id 0).
        $rest = \WC_Shipping_Zones::get_zone_by('zone_id', 0);
        if ($rest instanceof \WC_Shipping_Zone) {
            $best = $this->lowestMinimum($rest->get_shipping_methods(true), $best);
        }

        return $best;
    }

    /**
     * The lowest usable minimum among $methods, starting from $best (0.0 means
     * none yet). Remembers whether the winning method ignores discounts.
     *
     * @param iterable<mixed> $methods
     */
    private function lowestMinimum(iterable $methods, float $best = 0.0): float
    {
        foreach ($methods as $method) {
            $amount = $this->methodMinAmount($method);

            if ($amount > 0.0 && ($best === 0.0 || $amount < $best)) {
                $best = $amount;
                // methodMinAmount() only returns > 0 for a WC_Shipping_Method.
                $this->ignoreDiscounts = $method instanceof \WC_Shipping_Method
                    && 'yes' === $method->get_option('ignore_discounts');
            }
        }

        return $best;
    }

    /**
     * Extract a usable minimum order amount from a single shipping method, or
     * 0.0 if the method is not an enabled, amount-based free-shipping method.
     *
     * @param mixed $method A WC_Shipping_Method instance (loosely typed: the WC
     *                      zone API returns these in mixed-shaped arrays).
     */
    private function methodMinAmount(mixed $method): float
    {
        if (! $method instanceof \WC_Shipping_Method) {
            return 0.0;
        }

        if ($method->id !== 'free_shipping') {
            return 0.0;
        }

        if (! $method->is_enabled()) {
            return 0.0;
        }

        $requires = (string) $method->get_option('requires');

        // Only "min_amount" and "either"/"both" requirements expose a threshold
        // we can count down to. A coupon-only requirement has no amount goal.
        if (! in_array($requires, ['min_amount', 'either', 'both'], true)) {
            return 0.0;
        }

        $min = (float) wc_format_decimal((string) $method->get_option('min_amount'));

        return $min > 0.0 ? $min : 0.0;
    }

    /**
     * The cart subtotal used to measure progress. Uses the cart contents total
     * (items only, before shipping) so the goal mirrors how WooCommerce itself
     * evaluates the free-shipping minimum order amount.
     */
    public function cartTotal(): float
    {
        if (! function_exists('WC') || ! WC()->cart instanceof \WC_Cart) {
            return 0.0;
        }

        $cart = WC()->cart;

        // Match WooCommerce's own free-shipping calculation: contents total
        // (optionally minus discounts, per the store's "coupons reduce" setting).
        $total = (float) $cart->get_displayed_subtotal();

        if ($cart->display_prices_including_tax()) {
            $total = round($total - (float) $cart->get_discount_tax(), wc_get_price_decimals());
        }

        if (! $this->ignoreDiscounts) {
            $total = round($total - (float) $cart->get_discount_total(), wc_get_price_decimals());
        }

        return max(0.0, $total);
    }
}
