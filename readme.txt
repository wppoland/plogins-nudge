=== Plogins Nudge - Free Shipping Bar for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, free shipping, cart, progress bar, conversions
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.1
Requires Plugins: woocommerce
Stable tag: 1.0.11
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A free-shipping progress bar that tells WooCommerce shoppers how much more to add to qualify for free delivery.

== Description ==

Nudge shows shoppers how far their cart is from your free-shipping threshold and
how much more they need to add to reach it. The message updates as the cart
changes, so "free shipping over $50" stops being fine print and becomes a number
the customer can act on.

By default the threshold comes straight from WooCommerce. Nudge looks at your
enabled free-shipping methods across all shipping zones and uses the lowest
minimum order amount it finds, so you don't maintain the figure in two places. If
you'd rather set it yourself, switch to Manual mode and type a fixed amount; that
amount is also used as a fallback when Automatic mode finds no qualifying method.

When there's nothing useful to show (the bar is disabled, the cart is empty, or no
threshold is configured) Nudge renders nothing instead of an empty or
always-finished bar.

= Documentation and links =

* **Documentation**: [plogins.com/plogins-nudge/docs/](https://plogins.com/plogins-nudge/docs/)
* **Plugin page**: [plogins.com/plogins-nudge/](https://plogins.com/plogins-nudge/)
* **Source code**: [github.com/wppoland/plogins-nudge](https://github.com/wppoland/plogins-nudge)
* **Bug reports and feature requests**: [github.com/wppoland/plogins-nudge/issues](https://github.com/wppoland/plogins-nudge/issues)


= What it does =

* Reads the free-shipping threshold automatically from your active WooCommerce
  free-shipping methods, or takes a fixed amount you set by hand.
* Re-renders with the cart on the classic Cart and Checkout pages (the ones
  built with the `[woocommerce_cart]` and `[woocommerce_checkout]` shortcodes).
  A small script (no jQuery of its own) animates the width between updates.
* Exposes a real `role="progressbar"` with `aria-valuenow`/`min`/`max` and a
  readable text message for screen readers; honours `prefers-reduced-motion`.
* Reserves the bar's height before paint, so adding it doesn't shift the layout.
* Styles the bar with `--nudge-*` CSS custom properties and adapts to dark colour
  schemes, so themes can recolour it without editing markup.
* Lets you write the progress and success messages, with an `{amount}` token in
  either one for the remaining total.
* Ships a POT file, removes its options on uninstall, and declares HPOS
  compatibility.

Source code and bug reports live on GitHub: [github.com/wppoland/plogins-nudge](https://github.com/wppoland/plogins-nudge)

== Installation ==

1. Upload the plugin to `/wp-content/plugins/nudge`, or install via Plugins > Add New.
2. Activate it. WooCommerce must be active.
3. Go to **WooCommerce > Nudge**, enable the bar, and choose where it shows.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Yes. Nudge does nothing until WooCommerce is active.

= Where does the free-shipping amount come from? =

In Automatic mode, Nudge reads the minimum order amount from your enabled
WooCommerce free-shipping methods and uses the smallest one across your shipping
zones. In Manual mode you set a fixed amount yourself. If Automatic mode finds no
method with a minimum order amount, it uses the manual amount instead.

= What shows when no free-shipping goal is configured? =

Nothing. Rather than render an empty or always-complete bar, Nudge skips output
entirely until there's a real threshold to count down to.

= Does it work with the Cart and Checkout blocks? =

Not yet. The bar renders on the classic Cart and Checkout pages, the ones built
with the `[woocommerce_cart]` and `[woocommerce_checkout]` shortcodes. It is
safe alongside the blocks and declares Cart/Checkout Blocks compatibility, but
it draws nothing inside them. If your pages use the blocks, the Nudge settings
screen tells you so next to the placement checkboxes.

= Can I change the wording and colours? =

Yes. The progress and success messages are editable on the settings screen, and
the bar's colours and sizing are `--nudge-*` CSS custom properties your theme can
override.


= Does this plugin work on WordPress Multisite? =

Yes. This plugin is compatible with WordPress Multisite. Network activate it or activate it on individual sites; each site keeps its own settings and data.

== Screenshots ==

1. The free-shipping progress bar on the cart.
2. The Nudge settings screen.

== External Services ==

Nudge does not connect to any external service. It does not send analytics, register a licence, load remote fonts or scripts, or make any HTTP request off your server. Everything it needs (your free-shipping threshold and cart totals) comes from WooCommerce on the same site, and the bar's stylesheet and small animation script are served from the plugin folder, not a CDN. The only data Nudge stores is two WordPress options on your own database (`nudge_settings` for your configuration and `nudge_db_version` for upgrades), both removed when you delete the plugin.

== Translations ==

Plogins Nudge is fully translatable and ships the `plogins-nudge.pot` template. Translations are delivered by WordPress.org language packs from translate.wordpress.org, which is where Polish, German and Spanish are being contributed; the package itself carries no compiled translation files.

== Changelog ==

= 1.0.11 =
* Fixed: the PRO upgrade promo kept selling to people who had already bought the paid edition. Only the banner could be dismissed, so the sidebar promo and the locked feature cards followed a paying customer around for good. The promo now checks whether the paid edition is active and steps aside when it is.
* Fixed: arrow glyphs in the admin menu paths, and in the strings handed to translators. An arrow inside a translatable string makes the glyph every translator's problem and changes the layout in any locale that drops it.

= 1.0.10 =
* Fixed: deleting the plugin left the per-user "dismiss" flag from the PRO notice in the database. Uninstall now removes it for every user, not just the one who dismissed it.

= 1.0.9 =
* Fixed the two bar messages being stuck in English on a translated shop. "Add {amount} more to get free shipping!" and "You have unlocked free shipping!" were plain strings in a config file, not translatable strings, so they never reached the translation template and no language pack could ever replace them. They are translatable now, so the bar follows the site language as soon as a translation exists, without touching the settings. Translations come as WordPress.org language packs rather than in this download, so the bar stays English until a pack is published. Your own wording, if you typed one, is left exactly as you wrote it.

= 1.0.8 =
* Renamed to Plogins Nudge - Free Shipping Bar for WooCommerce so the name leads with the brand rather than a generic word, which is what the WordPress.org plugin review team asks for. The plugin slug is unchanged.

= 1.0.7 =
* Tested against WordPress 7.1. Verified by activating this build on a clean 7.1 install with WooCommerce 11.1, not by editing the header.

= 1.0.6 =
* Fixed the PRO promo on the settings screen quoting a price in PLN. PRO is priced and charged in EUR, so an admin on a Polish site was shown a zloty amount and then billed in euro, and the zloty figure was a fixed conversion that drifted from the real charge as the rate moved. The promo now shows the euro price that is actually taken.

= 1.0.5 =
* The settings screen now warns you when your cart or checkout page is built with the WooCommerce Cart or Checkout block, where the bar cannot appear, instead of leaving the placement box ticked with nothing showing on the storefront.
* The `{amount}` token now works in the success message too, so it no longer reaches shoppers as literal text.
* Corrected the plugin description: the bar renders on the classic cart and checkout pages, not inside the Cart and Checkout blocks.

= 1.0.3 =
* Translations: completed Polish, German and Spanish for the PRO upgrade panel.

= 1.0.2 =
* Added bundled Polish, German and Spanish translations for the plugin interface.

= 1.0.1 =
* First stable release.

= 0.1.4 =
* Renamed to Plogins Nudge for WooCommerce for a more distinctive plugin name.

= 0.1.3 =
* `nudge/bar_rendered` action and `data-nudge-placement` attribute for PRO analytics beacons.

= 0.1.2 =
* `nudge/bar_context` filter so PRO can adjust progress, messages and tier markers.

= 0.1.1 =
* `nudge/threshold` filter and `ThresholdResolver::zoneThreshold()` for PRO per-zone goals.

= 0.1.0 =
* First release: free-shipping progress bar for the cart and checkout, with an automatic or manual threshold, live updates as the cart changes, editable messages, dark-mode and reduced-motion support, and a settings screen under WooCommerce > Nudge.
