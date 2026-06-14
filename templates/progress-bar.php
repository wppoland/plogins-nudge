<?php
/**
 * Free-shipping progress bar (storefront).
 *
 * Accessible: the track is a role="progressbar" with aria-valuenow/min/max, and
 * the human-readable message is the text alternative announced to screen
 * readers. The fill width is set inline so the bar is correct on first paint
 * (no layout shift); the bundled script animates subsequent updates. Colours are
 * CSS custom properties on the stylesheet, so themes can override them.
 *
 * @package Nudge
 *
 * @var string $context  Render context: cart|checkout|inline.
 * @var int    $percent  Progress towards the goal, 0–100.
 * @var bool   $reached  Whether the free-shipping goal is met.
 * @var string $message  Pre-built message HTML (may contain wc_price markup).
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Variables are local to the template include scope, not true globals.

$context = isset($context) ? sanitize_html_class((string) $context) : 'cart';
$percent = isset($percent) ? max(0, min(100, (int) $percent)) : 0;
$reached = ! empty($reached);
$message = isset($message) ? (string) $message : '';

$wrapperClasses = 'nudge nudge--' . $context . ($reached ? ' is-complete' : '');
?>
<div class="<?php echo esc_attr($wrapperClasses); ?>" data-nudge>
    <p class="nudge__message" data-nudge-message>
        <?php echo wp_kses_post($message); ?>
    </p>
    <div
        class="nudge__track"
        role="progressbar"
        aria-valuemin="0"
        aria-valuemax="100"
        aria-valuenow="<?php echo esc_attr((string) $percent); ?>"
        aria-label="<?php esc_attr_e('Progress towards free shipping', 'nudge'); ?>"
    >
        <span
            class="nudge__fill"
            data-nudge-fill
            style="width:<?php echo esc_attr((string) $percent); ?>%;"
        ></span>
    </div>
</div>
