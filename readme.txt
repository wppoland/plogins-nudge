=== Shiplume - Shipping Progress Bar for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, free shipping, cart, progress bar, conversions
Requires at least: 6.5
Tested up to: 7.1
Requires PHP: 8.1
Requires Plugins: woocommerce
Stable tag: 1.2.5
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Show shoppers how much more to add for free shipping, with a progress bar on the cart and checkout, classic or block.

== Description ==

Shiplume shows shoppers how far their cart is from your free-shipping threshold and
how much more they need to add to reach it. The message updates as the cart
changes, so "free shipping over $50" stops being fine print and becomes a number
the customer can act on.

By default the threshold comes straight from WooCommerce. Shiplume looks at your
enabled free-shipping methods across all shipping zones and uses the lowest
minimum order amount it finds, so you don't maintain the figure in two places. If
you'd rather set it yourself, switch to Manual mode and type a fixed amount; that
amount is also used as a fallback when Automatic mode finds no qualifying method.

When there's nothing useful to show (the bar is disabled, the cart is empty, or no
threshold is configured) Shiplume renders nothing instead of an empty or
always-finished bar.

= Documentation and links =

* **Documentation**: [plogins.com/shiplume/docs/](https://plogins.com/shiplume/docs/)
* **Plugin page**: [plogins.com/shiplume/](https://plogins.com/shiplume/)
* **Source code**: [github.com/wppoland/shiplume](https://github.com/wppoland/shiplume)
* **Bug reports and feature requests**: [github.com/wppoland/shiplume/issues](https://github.com/wppoland/shiplume/issues)
* **Shiplume Pro**: [plogins.com/shiplume-pro/](https://plogins.com/shiplume-pro/)


= What it does =

* Reads the free-shipping threshold automatically from your active WooCommerce
  free-shipping methods, or takes a fixed amount you set by hand.
* Shows on the Cart and Checkout pages, whether they use the classic
  `[woocommerce_cart]` and `[woocommerce_checkout]` shortcodes or the Cart and
  Checkout blocks. On the blocks the bar sits above the block and updates after
  a quantity change, coupon or removal without a page reload.
* Can also show in the classic mini-cart widget, above its buttons (off by
  default), and anywhere you place the `[shiplume]` shortcode.
* A small script (no jQuery of its own) animates the width between updates.
* Exposes a real `role="progressbar"` with `aria-valuenow`/`min`/`max` and a
  readable text message for screen readers; honours `prefers-reduced-motion`.
* Reserves the bar's height before paint, so adding it doesn't shift the layout.
* Styles the bar with `--nudge-*` CSS custom properties and adapts to dark colour
  schemes, so themes can recolour it without editing markup.
* Lets you write the progress and success messages, with an `{amount}` token in
  either one for the remaining total.
* Ships a POT file, removes its options on uninstall, and declares HPOS
  compatibility.

Source code and bug reports live on GitHub: [github.com/wppoland/shiplume](https://github.com/wppoland/shiplume)

= Works with other Plogins plugins =

* [Sojlo - Minimum Order for WooCommerce](https://plogins.com/sojlo/) - below Sojlo's minimum order total, the bar says "Add X more to reach the minimum order of Y." instead of the free-shipping message.
* [Prenejo - Local Pickup for WooCommerce](https://plogins.com/prenejo/) - the bar is hidden while a local pickup method is chosen, and comes back when the shopper picks delivery. This keys off WooCommerce local pickup, so it works without Prenejo too.

== Shiplume Pro ==

Shiplume Pro is a separate paid plugin that extends Shiplume.

Included in Shiplume:

* Automatic threshold
* Live updates
* Cart and checkout
* Accessible
* No layout shift

Shiplume Pro adds:

* Floating mini-cart bar
* Per-zone goals
* Tiered rewards
* Conversion analytics

The free edition is not a trial and its features stay available without a time limit.

* **Shiplume Pro** - [plogins.com/shiplume-pro/](https://plogins.com/shiplume-pro/)
* **Compare editions and pricing** - [plogins.com/shiplume-pro/pricing/](https://plogins.com/shiplume-pro/pricing/)
* **PRO documentation** - [plogins.com/shiplume-pro/docs/](https://plogins.com/shiplume-pro/docs/)

== Installation ==

1. Upload the plugin to `/wp-content/plugins/shiplume`, or install via Plugins > Add New.
2. Activate it. WooCommerce must be active.
3. Go to **WooCommerce > Shiplume**, enable the bar, and choose where it shows.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Yes. Shiplume does nothing until WooCommerce is active.

= Where does the free-shipping amount come from? =

In Automatic mode, Shiplume reads the minimum order amount from your enabled
WooCommerce free-shipping methods and uses the smallest one across your shipping
zones. In Manual mode you set a fixed amount yourself. If Automatic mode finds no
method with a minimum order amount, it uses the manual amount instead.

= What shows when no free-shipping goal is configured? =

Nothing. Rather than render an empty or always-complete bar, Shiplume skips output
entirely until there's a real threshold to count down to.

= Does it work with the Cart and Checkout blocks? =

Yes. On a page built with the Cart or Checkout block the bar shows above the
block. It reads the same goal and messages as the classic pages and updates when
the shopper changes a quantity, applies a coupon or removes an item, without a
page reload. The Cart page and Checkout page checkboxes control both kinds of
page.

= Can I show the bar somewhere else? =

Yes. Tick "Mini-cart widget" to show it in the classic mini-cart widget, above
its buttons; it refreshes with the widget. The `[shiplume]` shortcode prints the
same bar anywhere a shortcode works. It shows the cart as it is when the page
loads and updates after an add to cart. The Mini-cart block used by block themes
is not covered.

= Can I change the wording and colours? =

Yes. The progress and success messages are editable on the settings screen, and
the bar's colours and sizing are `--nudge-*` CSS custom properties your theme can
override.


= Does it work with Sojlo and local pickup? =

Yes, with no setting. Below a Sojlo minimum order total, the bar says how much is missing for the minimum and is never shown as complete. While a local pickup method is chosen, including Prenejo's pickup, the bar is hidden on every placement.

= Does this plugin work on WordPress Multisite? =

Yes. This plugin is compatible with WordPress Multisite. Network activate it or activate it on individual sites; each site keeps its own settings and data.

== Screenshots ==

1. The cart with one progress bar above the totals, showing how much more to add for free shipping.
2. The Shiplume settings screen: where the bar shows, the free-shipping threshold and the messages, with the PRO features listed alongside.
3. The same bar at the top of the checkout.
4. The bar above the WooCommerce Cart block.

== External Services ==

Shiplume does not connect to any external service. It does not send analytics, register a licence, load remote fonts or scripts, or make any HTTP request off your server. Everything it needs (your free-shipping threshold and cart totals) comes from WooCommerce on the same site, and the bar's stylesheet and small animation script are served from the plugin folder, not a CDN. The only data Shiplume stores is two WordPress options on your own database (`nudge_settings` for your configuration and `nudge_db_version` for upgrades), both removed when you delete the plugin.

== Translations ==

Shiplume is fully translatable and ships the `shiplume.pot` template. Translations are delivered by WordPress.org language packs from translate.wordpress.org, which is where Polish, German and Spanish are being contributed; the package itself carries no compiled translation files.

== Changelog ==

= 1.2.5 =
* With Sojlo active and a minimum order total set, a cart under that minimum shows how much is missing for the minimum instead of the free-shipping message.
* The bar is hidden while a local pickup shipping method is chosen (WooCommerce local pickup, also used by Prenejo), and comes back when the shopper switches to a delivered method. On the classic checkout it now returns after being hidden.

= 1.2.4 =
* Links point at the new plogins.com address and the renamed GitHub repository.

= 1.2.3 =
* The Upgrade to PRO links open the pricing page in the admin's language where plogins.com has one, and tell plogins.com which plugin the visit came from (utm_source, utm_medium and utm_campaign in the link). Nothing is sent from your site.
* Readme: the PRO section lists what the free plugin includes and what the paid edition adds as two lists, which WordPress.org can display (it does not display tables).

= 1.2.2 =
* Readme only: a PRO section with links to the paid edition.

= 1.2.1 =
* The settings screen names the floating bar feature the same way as the plugin page does.

= 1.2.0 =
* New: the bar shows on the Cart and Checkout blocks, above the block, and updates after a quantity change, coupon or removal without a reload. It used to draw nothing on a block cart or checkout.
* New: "Mini-cart widget" setting shows the bar in the classic mini-cart widget, above its buttons. Off by default.
* New: the `[shiplume]` shortcode prints the bar anywhere a shortcode works and updates after an add to cart.

= 1.1.7 =
* Fixed: a store with no free-shipping method no longer shows "Add X more to get free shipping!". The manual amount defaulted to 50, so Automatic mode fell back to a goal WooCommerce never honours. The manual amount now starts at 0; a store that saved its settings keeps the amount it saved.
* Fixed: when the free-shipping method has "Apply minimum order rule before coupon discount" ticked, progress is measured before coupons, as WooCommerce does. The bar used to ask for more while WooCommerce already offered free shipping.
* Fixed: the progress bar reaches screen readers with its value. aria-valuenow, aria-valuemin and aria-valuemax were stripped from the output.
* Fixed: the bar animates again after the cart or checkout updates. The script listened natively for events WooCommerce fires through jQuery, which never reached it.

= 1.1.6 =
* Fixed: the bar showed twice on the classic cart and checkout, once above the totals and once inside them. On checkout a copy was added on every refresh. There is now one bar per page, and the checkout bar still updates when a coupon changes the total.
* Fixed: the bar's placement class and data attribute printed "Array" instead of cart or checkout.

= 1.1.5 =
* Display name is now Shiplume - Shipping Progress Bar for WooCommerce. The slug, settings and hooks are unchanged.

= 1.1.4 =
* The bar markup is escaped with wp_kses_post() where it is printed, instead of relying on the template alone.

= 1.1.3 =
* The upgrade notice's "Coming soon" and "Get notified" labels are English source strings for every language; Polish sites used to get their own Polish source text, which translators in other languages then saw untranslated.

= 1.1.2 =
* Renamed to Shiplume, a plain English name in place of the Esperanto one. The text domain and the plugin folder follow the name; the stored settings, options and every hook are unchanged.

= 1.1.1 =
* The sidebar upgrade promo now follows the same dismissal as the banner. Dismissing the banner used to leave a full-height advert on the settings screen for good, which is not what the WordPress.org guideline on upgrade prompts means by used with moderation.

= 1.1.0 =
* Renamed to Instigo. The WordPress.org review team asks a plugin name to lead with a distinctive, coined identifier rather than a generic descriptive word. Instigo is Esperanto for an urging. The text domain follows the name; the stored data, the settings and every hook are unchanged.

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
