<?php

declare(strict_types=1);

namespace Nudge;

defined('ABSPATH') || exit;

/**
 * Idempotent schema/version migrations, run on every boot. Compares a stored
 * option against VERSION and applies forward steps as needed.
 */
final class Migrator
{
    private const OPTION   = 'nudge_db_version';
    private const SETTINGS = 'nudge_settings';

    /**
     * The English messages that shipped as packaged defaults up to 1.0.8 and
     * could land in the option whenever the settings screen was saved.
     *
     * @var array<string, string>
     */
    private const LEGACY_TEXTS = [
        'message_progress' => 'Add {amount} more to get free shipping!',
        'message_success'  => 'You have unlocked free shipping!',
    ];

    public function maybeMigrate(): void
    {
        $current = (string) get_option(self::OPTION, '0');

        if (version_compare($current, VERSION, '>=')) {
            return;
        }

        $this->clearUntranslatableTexts();

        update_option(self::OPTION, VERSION, false);
    }

    /**
     * Clear a stored message that is byte for byte the old English default.
     *
     * Those values could never be translated: they came from a config array,
     * not a gettext call, so a shop running in Polish showed English however
     * complete the language pack was. Empty means "use the translated default",
     * which is what the settings screen's placeholder already promises.
     *
     * Only an exact match is cleared, so a merchant's own wording, including a
     * hand translation of the English one, survives untouched.
     */
    private function clearUntranslatableTexts(): void
    {
        $stored = get_option(self::SETTINGS, null);

        if (! is_array($stored)) {
            return;
        }

        $changed = false;

        foreach (self::LEGACY_TEXTS as $key => $legacy) {
            if (isset($stored[$key]) && (string) $stored[$key] === $legacy) {
                $stored[$key] = '';
                $changed      = true;
            }
        }

        if ($changed) {
            // null keeps the option's existing autoload flag. Passing false here
            // would quietly move the settings out of the autoloaded set on every
            // shop that took this update, which is not a change a text sweep gets
            // to make.
            update_option(self::SETTINGS, $stored, null);
        }
    }
}
