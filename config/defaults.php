<?php
/**
 * Default settings, merged under the option key `nudge_settings`.
 *
 * Nudge ships enabled. The threshold source defaults to "auto" — reading the
 * minimum order amount from an active WooCommerce free-shipping method — and
 * falls back to the manual amount when no such method is configured. The
 * merchant tunes the messages (with the {amount} token) and where the bar
 * appears from the Nudge admin screen.
 *
 * @package Nudge
 *
 * @return array<string, mixed>
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

return [
    'enabled' => true,

    // Threshold source: 'auto' (from a free-shipping method) or 'manual'.
    'threshold_source' => 'auto',

    // Manual free-shipping threshold (used when source is 'manual', or as the
    // fallback when 'auto' finds no configured free-shipping method).
    'manual_threshold' => 50.0,

    // Where the bar renders.
    'show_on_cart'     => true,
    'show_on_checkout' => true,

    // Messages. {amount} is replaced with the formatted remaining amount.
    'message_progress' => 'Add {amount} more to get free shipping!',
    'message_success'  => 'You have unlocked free shipping!',
];
