<?php
/**
 * PRO upsell content, generated from the plogins.com registry by
 * scripts/gen-pro-upsell.mjs. The admin upsell renders this; curate the
 * feature list to fit this plugin's settings screen (do not invent features).
 *
 * @package plogins-nudge-pro
 */

defined('ABSPATH') || exit;

return [
    'name'       => 'Nudge Pro',
    'url'        => 'https://plogins.com/plogins-nudge-pro/pricing/',
    'sellable'   => true,
    'price_from' => 19,
    'currency'   => 'EUR',
    'price_pln'  => 85,
    'lead'       => [
        'en' => 'Floating bar, per-zone goals, tiered rewards and conversion analytics ship in the current PRO release.',
        'pl' => 'Pływający pasek, cele per strefa, nagrody progowe i analityka konwersji są dostępne w bieżącym wydaniu PRO.',
    ],
    'features'   => [
        [
            'en' => ['title' => 'Floating mini-cart bar', 'desc' => 'A free-shipping progress bar on every storefront page, with position, message and colour controls.'],
            'pl' => ['title' => 'Pływający pasek mini-koszyka', 'desc' => 'Pasek postępu do darmowej wysyłki na każdej stronie sklepu, z konfiguracją pozycji, komunikatu i kolorów.'],
        ],
        [
            'en' => ['title' => 'Per-zone goals', 'desc' => 'Different free-shipping thresholds matched to the customer\'s shipping zone instead of a global minimum.'],
            'pl' => ['title' => 'Cele per strefa', 'desc' => 'Różne progi darmowej wysyłki dopasowane do strefy wysyłki klienta zamiast globalnego minimum.'],
        ],
        [
            'en' => ['title' => 'Tiered rewards', 'desc' => 'Chain up to three cart-total milestones with messages and bar markers.'],
            'pl' => ['title' => 'Nagrody progowe', 'desc' => 'Łańcuch do trzech kamieni milowych wartości koszyka z komunikatami i znacznikami na pasku.'],
        ],
        [
            'en' => ['title' => 'Conversion analytics', 'desc' => 'Track bar views and threshold hits per placement on WooCommerce → Nudge Analytics.'],
            'pl' => ['title' => 'Analityka konwersji', 'desc' => 'Zliczaj wyświetlenia paska i osiągnięcia progu per miejsce na WooCommerce → Nudge Analytics.'],
        ],
        [
            'en' => ['title' => 'PRO settings', 'desc' => 'Enable the floating bar, per-zone goals and tiered rewards under WooCommerce → Nudge Pro.'],
            'pl' => ['title' => 'Ustawienia PRO', 'desc' => 'Włącz pływający pasek, progi per strefa i nagrody progowe w WooCommerce → Nudge Pro.'],
        ],
    ],
];
