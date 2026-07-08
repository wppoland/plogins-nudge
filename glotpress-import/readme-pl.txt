=== Plogins Nudge - Free Shipping Bar for WooCommerce ===
Contributors: motylanogha
Tags: woocommerce, free shipping, cart, progress bar, conversions
Requires at least: 6.5
Tested up to: 7.0
Requires PHP: 8.1
Wymaga wtyczek: woocommerce
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Pasek postępu bezpłatnej wysyłki, który informuje kupujących WooCommerce, ile jeszcze dodać, aby zakwalifikować się do bezpłatnej dostawy.

== Description ==

Posunięcie pokazuje kupującym, jak daleko ich koszyk dzieli od progu bezpłatnej wysyłki
ile jeszcze muszą dodać, żeby to osiągnąć. Wiadomość aktualizuje się wraz z koszykiem
się zmienia, więc „bezpłatna wysyłka powyżej 50 USD” przestaje być drobnym drukiem i staje się liczbą
klient może działać.

Domyślnie próg pochodzi bezpośrednio z WooCommerce. Nudge patrzy na twoje
włączył metody bezpłatnej wysyłki we wszystkich strefach wysyłki i korzysta z najniższej
minimalna kwota zamówienia, którą znajdzie, więc nie utrzymujesz tej kwoty w dwóch miejscach. Jeśli
wolisz ustawić to sam, przejdź do trybu ręcznego i wpisz stałą kwotę; to
kwota jest również używana jako rezerwa, gdy tryb automatyczny nie znajdzie metody kwalifikującej.

Gdy nie ma nic przydatnego do pokazania (pasek jest wyłączony, koszyk jest pusty lub nie
próg jest skonfigurowany) Nudge nie renderuje niczego zamiast pustego lub
zawsze gotowy bar.

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-nudge/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-nudge/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-nudge
* <strong>Raporty o błędach i prośby o nowe funkcje</strong> - https://github.com/wppoland/plogins-nudge/issues


= What it does =

* Automatycznie odczytuje próg bezpłatnej wysyłki z aktywnego WooCommerce
  metody bezpłatnej wysyłki lub pobiera stałą kwotę ustawioną ręcznie.
* Ponowne renderowanie koszyka zarówno na klasycznych stronach koszyka/kasy, jak i na stronie
  Bloki koszyka/kasy. Mały skrypt (bez własnego jQuery) animuje szerokość
  pomiędzy aktualizacjami.
* Wyświetla prawdziwy `role="progressbar"` z `aria-valuenow`/`min`/`max` i
  czytelna wiadomość tekstowa dla czytników ekranu; honoruje „preferuje ograniczony ruch”.
* Rezerwuje wysokość paska przed malowaniem, więc dodanie go nie powoduje zmiany układu.
* Stylizuje pasek za pomocą niestandardowych właściwości CSS `--nudge-*` i dostosowuje się do ciemnego koloru
  schematy, dzięki czemu motywy mogą je ponownie pokolorować bez edytowania znaczników.
* Umożliwia zapisanie komunikatów o postępie i sukcesie z tokenem `{amount}`
  komunikat o postępie dotyczący pozostałej sumy.
* Wysyła plik POT, usuwa jego opcje podczas dezinstalacji i deklaruje HPOS i
  Kompatybilność z koszykiem/blokami kasowymi.

Kod źródłowy i raporty o błędach dostępne na GitHubie: https://github.com/wppoland/plogins-nudge

== Installation ==

1. Prześlij wtyczkę do `/wp-content/plugins/nudge` lub zainstaluj poprzez Wtyczki → Dodaj nową.
2. Aktywuj. WooCommerce musi być aktywny.
3. Przejdź do <strong>WooCommerce → Posuń</strong>, włącz pasek i wybierz, gdzie ma się wyświetlać.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Tak. Nudge nie robi nic, dopóki WooCommerce nie będzie aktywne.

= Where does the free-shipping amount come from? =

W trybie automatycznym Nudge odczytuje minimalną kwotę zamówienia z włączonej opcji
Metody bezpłatnej wysyłki WooCommerce i wykorzystuje najmniejszą z nich w całej wysyłce
strefy. W trybie ręcznym samodzielnie ustalasz stałą kwotę. Jeśli tryb automatyczny znajdzie nie
metoda z minimalną kwotą zamówienia, zamiast tego używa kwoty ręcznej.

= What shows when no free-shipping goal is configured? =

Nic. Zamiast renderować pusty lub zawsze kompletny pasek, Nudge pomija dane wyjściowe
aż do osiągnięcia rzeczywistego progu, do którego można odliczyć.

= Does it work with the Cart and Checkout blocks? =

Tak. Renderuje na klasycznych szablonach koszyka/kasy i na WooCommerce
Bloki koszyka/kasy i deklaruje kompatybilność z HPOS i blokami koszyka/kasy.

= Can I change the wording and colours? =

Tak. Komunikaty o postępie i sukcesie można edytować na ekranie ustawień
kolory i rozmiary paska to niestandardowe właściwości CSS `--nudge-*`, jakie może mieć Twój motyw
zastąpić.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest kompatybilna z WordPress Multisite. Aktywuj go w sieci lub aktywuj na poszczególnych stronach; każda witryna przechowuje własne ustawienia i dane.

== Screenshots ==

1. Pasek postępu bezpłatnej wysyłki w koszyku.
2. Ekran ustawień Nudge.

== External Services ==

Nudge nie łączy się z żadną usługą zewnętrzną. Nie wysyła danych analitycznych, nie rejestruje licencji, nie ładuje zdalnych czcionek ani skryptów ani nie wysyła żadnych żądań HTTP z serwera. Wszystko, czego potrzebuje (próg bezpłatnej wysyłki i suma koszyka) pochodzi z WooCommerce w tej samej witrynie, a arkusz stylów paska i mały skrypt animacji są udostępniane z folderu wtyczek, a nie z CDN. Jedyne dane, które Nudge przechowuje, to dwie opcje WordPress w Twojej własnej bazie danych („nudge_settings” dla Twojej konfiguracji i „nudge_db_version” dla aktualizacji), obie usunięte po usunięciu wtyczki.

== Changelog ==

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.1.4 =
* Zmieniono nazwę na Plogins Nudge dla WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.1.3 =
* Akcja „nudge/bar_rendered” i atrybut „data-nudge-placement” dla sygnalizatorów analitycznych PRO.

= 0.1.2 =
* Filtr `nudge/bar_context`, dzięki któremu PRO może dostosować postęp, komunikaty i znaczniki poziomów.

= 0.1.1 =
* Filtr „nudge/threshold” i „ThresholdResolver::zoneThreshold()” dla celów PRO na strefę.

= 0.1.0 =
* Pierwsza wersja: pasek postępu bezpłatnej wysyłki dla koszyka i kasy, z automatycznym lub ręcznym progiem, aktualizacje na żywo w miarę zmian w koszyku, edytowalne wiadomości, obsługa trybu ciemnego i ograniczonego ruchu oraz ekran ustawień w WooCommerce → Nudge.
