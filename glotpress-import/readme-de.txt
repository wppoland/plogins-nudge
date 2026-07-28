=== Plogins Nudge - Free Shipping Bar for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, free shipping, cart, progress bar, conversions
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Requires Plugins: woocommerce
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ein Fortschrittsbalken für kostenlosen Versand, der WooCommerce-Käuferinnen und -Käufern zeigt, wie viel sie noch hinzufügen müssen, um sich für die kostenlose Lieferung zu qualifizieren.

== Description ==

Nudge zeigt Käuferinnen und Käufern, wie weit ihr Warenkorb vom Schwellenwert für kostenlosen Versand entfernt ist und
wie viel sie noch hinzufügen müssen, um ihn zu erreichen. Die Nachricht aktualisiert sich mit dem Warenkorb,
sodass „kostenloser Versand ab 50 $“ kein Kleingedrucktes mehr ist, sondern eine Zahl,
auf die Kundinnen und Kunden reagieren können.

Standardmäßig kommt der Schwellenwert direkt aus WooCommerce. Nudge prüft deine
aktivierten Methoden für kostenlosen Versand in allen Versandzonen und nutzt den niedrigsten
gefundenen Mindestbestellwert, sodass du die Zahl nicht an zwei Stellen pflegen musst. Wenn
du sie lieber selbst festlegen möchtest, wechsle in den manuellen Modus und gib einen festen Betrag ein; dieser
Betrag dient auch als Fallback, wenn der automatische Modus keine qualifizierende Methode findet.

Wenn es nichts Sinnvolles zu zeigen gibt (der Balken ist deaktiviert, der Warenkorb ist leer oder
kein Schwellenwert ist konfiguriert), rendert Nudge nichts statt eines leeren oder
immer vollständigen Balkens.

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-nudge/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-nudge/
* <strong>Quellcode</strong> - https://github.com/wppoland/plogins-nudge
* <strong>Fehlerberichte und Funktionswünsche</strong> - https://github.com/wppoland/plogins-nudge/issues


= What it does =

* Liest den Schwellenwert für kostenlosen Versand automatisch aus deinen aktiven
  WooCommerce-Methoden für kostenlosen Versand oder übernimmt einen von dir manuell festgelegten Betrag.
* Rendert sich mit dem Warenkorb sowohl auf den klassischen Warenkorb-/Kassenseiten als auch auf den
  Warenkorb-/Kassenblöcken neu. Ein kleines Skript (ohne eigenes jQuery) animiert die Breite
  zwischen Updates.
* Stellt ein echtes `role="progressbar"` mit `aria-valuenow`/`min`/`max` und eine
  lesbare Textnachricht für Screenreader bereit; respektiert prefers-reduced-motion.
* Reserviert die Balkenhöhe vor dem Malen, sodass das Hinzufügen das Layout nicht verschiebt.
* Stylt den Balken mit den CSS-Custom-Properties `--nudge-*` und passt sich an dunkle
  Farbschemata an, sodass Themes ihn neu einfärben können, ohne Markup zu bearbeiten.
* Lässt dich Fortschritts- und Erfolgsmeldungen schreiben, mit einem `{amount}`-Token in der
  Fortschrittsmeldung für die verbleibende Summe.
* Liefert eine POT-Datei, entfernt seine Optionen bei der Deinstallation und deklariert HPOS- sowie
  Warenkorb-/Kassenblock-Kompatibilität.

Quellcode und Fehlerberichte liegen auf GitHub: https://github.com/wppoland/plogins-nudge

== Installation ==

1. Lade das Plugin nach `/wp-content/plugins/nudge` hoch oder installiere es über Plugins → Installieren.
2. Aktiviere es. WooCommerce muss aktiv sein.
3. Gehe zu <strong>WooCommerce → Nudge</strong>, aktiviere den Balken und wähle, wo er angezeigt wird.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Ja. Nudge tut nichts, bis WooCommerce aktiv ist.

= Where does the free-shipping amount come from? =

Im automatischen Modus liest Nudge den Mindestbestellwert aus deinen aktivierten
WooCommerce-Methoden für kostenlosen Versand und nutzt den kleinsten über alle Versand-
zonen hinweg. Im manuellen Modus legst du selbst einen festen Betrag fest. Findet der automatische Modus keine
Methode mit Mindestbestellwert, wird stattdessen der manuelle Betrag verwendet.

= What shows when no free-shipping goal is configured? =

Nichts. Statt einen leeren oder immer vollständigen Balken zu rendern, überspringt Nudge die Ausgabe
vollständig, bis es einen echten Schwellenwert gibt, auf den heruntergezählt werden kann.

= Does it work with the Cart and Checkout blocks? =

Ja. Es rendert auf den klassischen Warenkorb-/Kassenvorlagen und auf den WooCommerce-
Warenkorb-/Kassenblöcken und deklariert HPOS- sowie Warenkorb-/Kassenblock-Kompatibilität.

= Can I change the wording and colours? =

Ja. Fortschritts- und Erfolgsmeldungen kannst du auf dem Einstellungsbildschirm bearbeiten, und
Farben sowie Größe des Balkens sind `--nudge-*` CSS-Custom-Properties, die dein Theme
überschreiben kann.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es netzwerkweit oder auf einzelnen Websites; jede Website behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Der Fortschrittsbalken für kostenlosen Versand im Warenkorb.
2. Der Nudge-Einstellungsbildschirm.

== External Services ==

Nudge stellt keine Verbindung zu externen Diensten her. Es sendet keine Analysen, registriert keine Lizenz, lädt keine Remote-Schriftarten oder -Skripte und stellt keine HTTP-Anfrage von deinem Server. Alles, was es braucht (dein Schwellenwert für kostenlosen Versand und Warenkorbsummen), kommt von WooCommerce auf derselben Website, und das Stylesheet des Balkens sowie das kleine Animationsskript werden aus dem Plugin-Ordner ausgeliefert, nicht von einem CDN. Die einzigen Daten, die Nudge speichert, sind zwei WordPress-Optionen in deiner eigenen Datenbank (`nudge_settings` für deine Konfiguration und `nudge_db_version` für Upgrades), beide werden beim Löschen des Plugins entfernt.

== Translations ==

Plogins Nudge enthält polnische, deutsche und spanische Übersetzungen für die Plugin-Oberfläche. Die Textdomain ist `plogins-nudge`, sodass Sprachpakete von WordPress.org diese mitgelieferten Übersetzungen ebenfalls überschreiben oder erweitern können.

== Changelog ==

= 1.0.2 =
* Mitgelieferte polnische, deutsche und spanische Übersetzungen für die Plugin-Oberfläche hinzugefügt.

= 1.0.1 =
* Erste stabile Version.

= 0.1.4 =
* Umbenannt in Plogins Nudge for WooCommerce für einen unverwechselbaren Plugin-Namen.

= 0.1.3 =
* Aktion `nudge/bar_rendered` und Attribut `data-nudge-placement` für PRO-Analyse-Beacons.

= 0.1.2 =
* Filter `nudge/bar_context`, damit PRO Fortschritt, Nachrichten und Stufenmarker anpassen kann.

= 0.1.1 =
* Filter `nudge/threshold` und `ThresholdResolver::zoneThreshold()` für PRO-Ziele pro Zone.

= 0.1.0 =
* Erste Veröffentlichung: Fortschrittsbalken für kostenlosen Versand für Warenkorb und Kasse, mit automatischem oder manuellem Schwellenwert, Live-Updates bei Warenkorbänderungen, bearbeitbaren Nachrichten, Dark-Mode- und Reduced-Motion-Unterstützung sowie einem Einstellungsbildschirm unter WooCommerce → Nudge.
