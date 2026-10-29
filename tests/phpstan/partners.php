<?php
/**
 * Partner plugin classes Shiplume detects at runtime, stubbed for PHPStan.
 *
 * @package Nudge
 */

declare(strict_types=1);

namespace Minimum\Rules;

if (! class_exists(Settings::class)) {
    final class Settings
    {
        public function is_enabled(): bool
        {
            return false;
        }

        public function min_order_total(): float
        {
            return 0.0;
        }
    }
}
