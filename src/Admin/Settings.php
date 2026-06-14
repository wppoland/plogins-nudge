<?php

declare(strict_types=1);

namespace Nudge\Admin;

defined('ABSPATH') || exit;

use Nudge\Contract\HasHooks;

/**
 * Admin settings page registered as a WooCommerce submenu ("WooCommerce →
 * Nudge").
 *
 * Stores settings in the `nudge_settings` option (array): enable, where the bar
 * shows, the threshold source (auto vs manual) and the manual amount, and the
 * progress/success messages (with the {amount} token). All output is escaped;
 * all input is sanitised and clamped on save.
 */
final class Settings implements HasHooks
{
    private const OPTION = 'nudge_settings';
    private const PAGE   = 'nudge-settings';

    private const SOURCES = ['auto', 'manual'];

    public function registerHooks(): void
    {
        add_action('admin_menu', [$this, 'addMenuPage']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function addMenuPage(): void
    {
        add_submenu_page(
            'woocommerce',
            __('Nudge — Free Shipping Bar', 'nudge'),
            __('Nudge', 'nudge'),
            'manage_woocommerce',
            self::PAGE,
            [$this, 'renderPage'],
        );
    }

    public function registerSettings(): void
    {
        register_setting(
            self::PAGE,
            self::OPTION,
            [
                'type'              => 'array',
                'sanitize_callback' => [$this, 'sanitize'],
            ],
        );

        add_filter(
            'option_page_capability_' . self::PAGE,
            static fn (): string => 'manage_woocommerce',
        );
    }

    public function renderPage(): void
    {
        if (! current_user_can('manage_woocommerce')) {
            return;
        }

        $settings = $this->settings();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <p>
                <?php esc_html_e('A friendly progress bar that shows customers how close they are to free shipping — and how much more to add to unlock it. It updates live as the cart changes.', 'nudge'); ?>
            </p>

            <form method="post" action="options.php">
                <?php settings_fields(self::PAGE); ?>

                <h2><?php esc_html_e('General', 'nudge'); ?></h2>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><?php esc_html_e('Enable Nudge', 'nudge'); ?></th>
                            <td>
                                <label for="nudge_enabled">
                                    <input type="checkbox" id="nudge_enabled" name="<?php echo esc_attr(self::OPTION); ?>[enabled]" value="1" <?php checked((bool) ($settings['enabled'] ?? false), true); ?> />
                                    <?php esc_html_e('Show the free-shipping progress bar.', 'nudge'); ?>
                                </label>
                            </td>
                        </tr>
                        <?php
                        $this->checkboxRow('show_on_cart', __('Cart page', 'nudge'), __('Show the bar on the cart.', 'nudge'), $settings);
                        $this->checkboxRow('show_on_checkout', __('Checkout page', 'nudge'), __('Show the bar on checkout.', 'nudge'), $settings);
                        ?>
                    </tbody>
                </table>

                <h2><?php esc_html_e('Free-shipping threshold', 'nudge'); ?></h2>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="nudge_threshold_source"><?php esc_html_e('Threshold source', 'nudge'); ?></label>
                            </th>
                            <td>
                                <select id="nudge_threshold_source" name="<?php echo esc_attr(self::OPTION); ?>[threshold_source]">
                                    <?php
                                    $current      = (string) ($settings['threshold_source'] ?? 'auto');
                                    $sourceLabels = [
                                        'auto'   => __('Automatic (from free-shipping method)', 'nudge'),
                                        'manual' => __('Manual (fixed amount)', 'nudge'),
                                    ];
                                    foreach (self::SOURCES as $source) :
                                        ?>
                                        <option value="<?php echo esc_attr($source); ?>" <?php selected($current, $source); ?>>
                                            <?php echo esc_html($sourceLabels[$source] ?? $source); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description"><?php esc_html_e('Automatic reads the minimum order amount from your WooCommerce free-shipping method (the smallest one across your shipping zones). The manual amount is used as a fallback.', 'nudge'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="nudge_manual_threshold"><?php esc_html_e('Manual amount', 'nudge'); ?></label>
                            </th>
                            <td>
                                <input type="number" min="0" step="0.01" id="nudge_manual_threshold" name="<?php echo esc_attr(self::OPTION); ?>[manual_threshold]" value="<?php echo esc_attr((string) ($settings['manual_threshold'] ?? 50)); ?>" class="regular-text" />
                                <p class="description"><?php esc_html_e('In your store currency, before shipping and taxes.', 'nudge'); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <h2><?php esc_html_e('Messages', 'nudge'); ?></h2>
                <p class="description">
                    <?php
                    printf(
                        /* translators: %s: the {amount} token wrapped in <code>. */
                        esc_html__('Use %s in the progress message — it is replaced with the remaining amount, formatted in your store currency.', 'nudge'),
                        '<code>{amount}</code>',
                    );
                    ?>
                </p>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="nudge_message_progress"><?php esc_html_e('Progress message', 'nudge'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="nudge_message_progress" name="<?php echo esc_attr(self::OPTION); ?>[message_progress]" value="<?php echo esc_attr((string) ($settings['message_progress'] ?? '')); ?>" class="large-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="nudge_message_success"><?php esc_html_e('Success message', 'nudge'); ?></label>
                            </th>
                            <td>
                                <input type="text" id="nudge_message_success" name="<?php echo esc_attr(self::OPTION); ?>[message_success]" value="<?php echo esc_attr((string) ($settings['message_success'] ?? '')); ?>" class="large-text" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render a single checkbox row.
     *
     * @param array<string, mixed> $settings
     */
    private function checkboxRow(string $key, string $label, string $help, array $settings): void
    {
        $id = 'nudge_' . $key;
        ?>
        <tr>
            <th scope="row"><?php echo esc_html($label); ?></th>
            <td>
                <label for="<?php echo esc_attr($id); ?>">
                    <input type="checkbox" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr(self::OPTION); ?>[<?php echo esc_attr($key); ?>]" value="1" <?php checked((bool) ($settings[$key] ?? false), true); ?> />
                    <?php echo esc_html($help); ?>
                </label>
            </td>
        </tr>
        <?php
    }

    /**
     * Sanitises, validates and clamps the submitted settings before save.
     *
     * @param mixed $raw
     * @return array<string, mixed>
     */
    public function sanitize(mixed $raw): array
    {
        if (! is_array($raw)) {
            $raw = [];
        }

        $source = isset($raw['threshold_source']) ? sanitize_key((string) $raw['threshold_source']) : 'auto';
        if (! in_array($source, self::SOURCES, true)) {
            $source = 'auto';
        }

        return [
            'enabled'          => ! empty($raw['enabled']),
            'show_on_cart'     => ! empty($raw['show_on_cart']),
            'show_on_checkout' => ! empty($raw['show_on_checkout']),

            'threshold_source' => $source,
            'manual_threshold' => max(0.0, (float) wc_format_decimal((string) ($raw['manual_threshold'] ?? '0'))),

            'message_progress' => $this->text($raw, 'message_progress'),
            'message_success'  => $this->text($raw, 'message_success'),
        ];
    }

    /**
     * Sanitise a single text field, preserving the {amount} token.
     *
     * @param array<string, mixed> $raw
     */
    private function text(array $raw, string $key): string
    {
        return isset($raw[$key]) ? sanitize_text_field((string) $raw[$key]) : '';
    }

    /**
     * Stored settings merged over packaged defaults.
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

        return array_merge($defaults, $stored);
    }
}
