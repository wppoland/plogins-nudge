=== Plogins Nudge - Free Shipping Bar for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, free shipping, cart, progress bar, conversions
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Erfordert Plugins: woocommerce
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Ein Fortschrittsbalken für den kostenlosen Versand, der WooCommerce-Käufern sagt, wie viel sie noch hinzufügen müssen, um sich für den kostenlosen Versand zu qualifizieren.

== Description ==

Nudge zeigt Käufern an, wie weit ihr Warenkorb von deinem Schwellenwert für den kostenlosen Versand entfernt ist
Wie viel mehr müssen sie hinzufügen, um es zu erreichen? Die Nachricht wird als Warenkorb aktualisiert
ändert sich, sodass „kostenloser Versand über 50 $“ kein Kleingedrucktes mehr ist, sondern eine Zahl
Der Kunde kann darauf reagieren.

Standardmäßig kommt der Schwellenwert direkt von WooCommerce. Nudge sieht dich an
aktivierte kostenlose Versandmethoden für alle Versandzonen und nutzt die niedrigsten
Mindestbestellmenge, die es findet, sodass du die Zahl nicht an zwei Stellen pflegen. Wenn
Du möchten es lieber selbst einstellen, in den manuellen Modus wechseln und einen festen Betrag eingeben; das
Der Betrag wird auch als Ersatz verwendet, wenn der automatische Modus keine qualifizierende Methode findet.

Wenn es nichts Nützliches zu zeigen gibt (die Leiste ist deaktiviert, der Warenkorb ist leer oder nicht).
Schwellenwert konfiguriert ist) Nudge rendert nichts anstelle eines leeren oder
immer fertige Bar.

= Documentation and links =

* <strong>Dokumentation</strong> - https://plogins.com/de/plogins-nudge/docs/
* <strong>Plugin-Seite</strong> - https://plogins.com/de/plogins-nudge/
* <strong>Quellcode</strong> – https://github.com/wppoland/plogins-nudge
* <strong>Fehlerberichte und Funktionsanfragen</strong> – https://github.com/wppoland/plogins-nudge/issues


= What it does =

* Liest den Schwellenwert für den kostenlosen Versand automatisch aus deinem aktiven WooCommerce
  Kostenlose Versandmethoden oder ein von dir manuell festgelegter Festbetrag.
* Wird mit dem Warenkorb sowohl auf den klassischen Warenkorb-/Checkout-Seiten als auch auf den neu gerendert
  Warenkorb-/Kassenblöcke. Ein kleines Skript (kein eigenes jQuery) animiert die Breite
  zwischen Updates.
* Macht eine echte „role="progressbar"` mit `aria-valuenow`/`min`/`max` und a verfügbar
  lesbare Textnachricht für Screenreader; würdigt „bevorzugt reduzierte Bewegung“.
* Reserviert die Höhe der Leiste vor dem Malen, sodass das Layout durch das Hinzufügen nicht verschoben wird.
* Gestaltet die Leiste mit den benutzerdefinierten CSS-Eigenschaften „--nudge-*“ und passt sie an dunkle Farben an
  Schemata, sodass Themes es neu einfärben können, ohne das Markup bearbeiten zu müssen.
* Ermöglicht das Schreiben der Fortschritts- und Erfolgsmeldungen mit einem „{amount}“-Token
  die Fortschrittsmeldung für die verbleibende Gesamtsumme.
* Versendet eine POT-Datei, entfernt ihre Optionen bei der Deinstallation und deklariert HPOS und
  Kompatibilität von Warenkorb-/Checkout-Blöcken.

Quellcode und Fehlerberichte live auf GitHub: https://github.com/wppoland/plogins-nudge

== Installation ==

1. Lade das Plugin nach „/wp-content/plugins/nudge“ hoch oder installiere es über Plugins → Neu hinzufügen.
2. Aktiviere es. WooCommerce muss aktiv sein.
3. Gehe zu <strong>WooCommerce → Nudge</strong>, aktiviere die Leiste und wähle aus, wo sie angezeigt werden soll.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Ja. Nudge führt nichts aus, bis WooCommerce aktiv ist.

= Where does the free-shipping amount come from? =

Im automatischen Modus liest Nudge den Mindestbestellwert von deinem aktivierten Wert
WooCommerce bietet kostenlose Versandmethoden und verwendet die kleinste für deinen Versand
Zonen. Im manuellen Modus lege selbst einen festen Betrag fest. Wenn der Automatikmodus keine findet
Wenn du die Methode mit einem Mindestbestellwert verwenden, wird stattdessen der manuelle Betrag verwendet.

= What shows when no free-shipping goal is configured? =

Nichts. Anstatt einen leeren oder immer vollständigen Balken zu rendern, überspringt Nudge die Ausgabe
vollständig, bis es einen echten Schwellenwert gibt, bis zu dem heruntergezählt werden muss.

= Does it work with the Cart and Checkout blocks? =

Ja. Es wird auf den klassischen Warenkorb-/Checkout-Vorlagen und auf WooCommerce gerendert
Cart/Checkout-Blöcke und erklärt die Kompatibilität von HPOS und Cart/Checkout-Blöcken.

= Can I change the wording and colours? =

Ja. Die Fortschritts- und Erfolgsmeldungen können auf dem Einstellungsbildschirm bearbeitet werden
Die Farben und die Größe der Leiste sind „--nudge-*“ benutzerdefinierte CSS-Eigenschaften, die dein Theme verwenden kann
überschreiben.


= Does this plugin work on WordPress Multisite? =

Ja. Dieses Plugin ist mit WordPress Multisite kompatibel. Aktiviere es im Netzwerk oder auf einzelnen Websites. Jede Site behält ihre eigenen Einstellungen und Daten.

== Screenshots ==

1. Der Fortschrittsbalken für den kostenlosen Versand im Warenkorb.
2. Der Nudge-Einstellungsbildschirm.

== External Services ==

Nudge stellt keine Verbindung zu externen Diensten her. Es werden keine Analysen gesendet, keine Lizenz registriert, Remote-Schriftarten oder -Skripte geladen und keine HTTP-Anfrage von deinem Server gestellt. Alles, was es benötigt (dein kostenloser Versandschwellenwert und die Warenkorbsummen), kommt von WooCommerce auf derselben Website, und das Stylesheet der Leiste und das kleine Animationsskript werden vom Plugin-Ordner bereitgestellt, nicht von einem CDN. Die einzigen Daten, die Nudge speichert, sind zwei WordPress-Optionen in deiner eigenen Datenbank („nudge_settings“ für deine Konfiguration und „nudge_db_version“ für Upgrades), die beide entfernt werden, wenn du das Plugin löschen.

== Changelog ==

= 1.0.1 =
* Erste stabile Version.

= 0.1.4 =
* Für einen markanteren Plugin-Namen in Plogins Nudge für WooCommerce umbenannt.

= 0.1.3 =
* Aktion „nudge/bar_rendered“ und Attribut „data-nudge-placement“ für PRO-Analyse-Beacons.

= 0.1.2 =
* „nudge/bar_context“-Filter, damit PRO Fortschritt, Nachrichten und Stufenmarkierungen anpassen kann.

= 0.1.1 =
* „nudge/threshold“-Filter und „ThresholdResolver::zoneThreshold()“ für PRO-Ziele pro Zone.

= 0.1.0 =
* Erste Veröffentlichung: Fortschrittsbalken für den kostenlosen Versand für den Warenkorb und die Kasse, mit einem automatischen oder manuellen Schwellenwert, Live-Updates bei Änderungen im Warenkorb, bearbeitbare Nachrichten, Unterstützung für Dunkelmodus und reduzierte Bewegung sowie ein Einstellungsbildschirm unter WooCommerce → Nudge.
