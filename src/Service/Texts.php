<?php

declare(strict_types=1);

namespace Nudge\Service;

defined('ABSPATH') || exit;

/**
 * The customer-facing bar messages a merchant may override, in the language of
 * the site.
 *
 * They used to be English sentences in config/defaults.php, merged under the
 * stored settings on every render. A string in a config array is never wrapped
 * in a gettext call, so it never reaches the .pot and no translator can reach
 * it: a shop running in Polish showed "Add {amount} more to get free shipping!"
 * however complete the language pack was.
 *
 * The packaged default is now empty, meaning "use the string below". A merchant
 * who types their own still wins, and what they typed is stored as typed.
 */
final class Texts
{
    /**
     * Setting key => the translated default.
     *
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'message_progress' => __('Add {amount} more to get free shipping!', 'shiplume'),
            'message_success'  => __('You have unlocked free shipping!', 'shiplume'),
        ];
    }

    /**
     * Fill every empty message key with its translated default.
     *
     * Applied on the way OUT, where the string is about to be shown, and never
     * on the way in: writing the resolved text back to the option would freeze
     * one language into the database, which is the bug this class exists to fix.
     *
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    public static function apply(array $settings): array
    {
        foreach (self::defaults() as $key => $text) {
            if (trim((string) ($settings[$key] ?? '')) === '') {
                $settings[$key] = $text;
            }
        }

        return $settings;
    }
}
