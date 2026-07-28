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

Pasek postępu darmowej wysyłki, który pokazuje kupującym WooCommerce, ile jeszcze muszą dodać, aby uzyskać bezpłatną dostawę.

== Description ==

Nudge pokazuje kupującym, jak daleko ich koszyk jest od progu darmowej wysyłki i
ile jeszcze muszą dodać, aby go osiągnąć. Komunikat aktualizuje się wraz ze zmianami
w koszyku, więc „darmowa wysyłka od 50 USD” przestaje być drobnym drukiem i staje się liczbą,
na którą klient może zareagować.

Domyślnie próg pochodzi bezpośrednio z WooCommerce. Nudge sprawdza Twoje
włączone metody darmowej wysyłki we wszystkich strefach wysyłki i używa najniższej
znalezionej minimalnej kwoty zamówienia, więc nie musisz utrzymywać tej wartości w dwóch miejscach. Jeśli
wolisz ustawić ją samodzielnie, przełącz się na tryb ręczny i wpisz stałą kwotę; ta
kwota służy też jako zapasowa, gdy tryb automatyczny nie znajdzie kwalifikującej metody.

Gdy nie ma nic sensownego do pokazania (pasek jest wyłączony, koszyk jest pusty lub
nie skonfigurowano progu), Nudge nie renderuje niczego zamiast pustego lub
zawsze ukończonego paska.

= Documentation and links =

* <strong>Dokumentacja</strong> - https://plogins.com/pl/plogins-nudge/docs/
* <strong>Strona wtyczki</strong> - https://plogins.com/pl/plogins-nudge/
* <strong>Kod źródłowy</strong> - https://github.com/wppoland/plogins-nudge
* <strong>Zgłoszenia błędów i propozycje funkcji</strong> - https://github.com/wppoland/plogins-nudge/issues


= What it does =

* Odczytuje próg darmowej wysyłki automatycznie z aktywnych metod
  darmowej wysyłki WooCommerce albo przyjmuje stałą kwotę ustawioną ręcznie.
* Renderuje się ponownie wraz z koszykiem zarówno na klasycznych stronach Koszyk/Kasa, jak i na
  blokach Koszyk/Kasa. Mały skrypt (bez własnego jQuery) animuje szerokość
  między aktualizacjami.
* Udostępnia prawdziwy `role="progressbar"` z `aria-valuenow`/`min`/`max` oraz
  czytelny komunikat tekstowy dla czytników ekranu; respektuje prefers-reduced-motion.
* Rezerwuje wysokość paska przed malowaniem, więc dodanie go nie przesuwa układu.
* Stylizuje pasek za pomocą własnych właściwości CSS `--nudge-*` i dostosowuje się do ciemnych
  schematów kolorów, więc motywy mogą go przekolorować bez edycji znaczników.
* Pozwala napisać komunikaty postępu i sukcesu z tokenem `{amount}` w
  komunikacie postępu dla pozostałej sumy.
* Dołącza plik POT, usuwa swoje opcje przy dezinstalacji i deklaruje zgodność z HPOS oraz
  blokami Koszyk/Kasa.

Kod źródłowy i zgłoszenia błędów znajdziesz na GitHubie: https://github.com/wppoland/plogins-nudge

== Installation ==

1. Prześlij wtyczkę do `/wp-content/plugins/nudge` lub zainstaluj przez Wtyczki → Dodaj nową.
2. Włącz ją. WooCommerce musi być aktywne.
3. Przejdź do <strong>WooCommerce → Nudge</strong>, włącz pasek i wybierz, gdzie ma się wyświetlać.

== Frequently Asked Questions ==

= Does it require WooCommerce? =

Tak. Nudge nie robi nic, dopóki WooCommerce nie jest aktywne.

= Where does the free-shipping amount come from? =

W trybie automatycznym Nudge odczytuje minimalną kwotę zamówienia z włączonych
metod darmowej wysyłki WooCommerce i używa najmniejszej we wszystkich strefach
wysyłki. W trybie ręcznym sam ustawiasz stałą kwotę. Jeśli tryb automatyczny nie znajdzie
metody z minimalną kwotą zamówienia, używa zamiast tego kwoty ręcznej.

= What shows when no free-shipping goal is configured? =

Nic. Zamiast renderować pusty lub zawsze ukończony pasek, Nudge pomija wyjście
całkowicie, dopóki nie ma realnego progu, do którego można odliczać.

= Does it work with the Cart and Checkout blocks? =

Tak. Renderuje się na klasycznych szablonach Koszyk/Kasa oraz na blokach
Koszyk/Kasa WooCommerce i deklaruje zgodność z HPOS oraz blokami Koszyk/Kasa.

= Can I change the wording and colours? =

Tak. Komunikaty postępu i sukcesu możesz edytować na ekranie ustawień, a
kolory i rozmiar paska to własne właściwości CSS `--nudge-*`, które Twój motyw może
nadpisać.


= Does this plugin work on WordPress Multisite? =

Tak. Ta wtyczka jest zgodna z WordPress Multisite. Włącz ją dla całej sieci lub w pojedynczych witrynach; każda witryna zachowuje własne ustawienia i dane.

== Screenshots ==

1. Pasek postępu darmowej wysyłki w koszyku.
2. Ekran ustawień Nudge.

== External Services ==

Nudge nie łączy się z żadną usługą zewnętrzną. Nie wysyła analityki, nie rejestruje licencji, nie ładuje zdalnych czcionek ani skryptów i nie wykonuje żadnych żądań HTTP poza Twoim serwerem. Wszystko, czego potrzebuje (próg darmowej wysyłki i sumy koszyka), pochodzi z WooCommerce w tej samej witrynie, a arkusz stylów paska i mały skrypt animacji są serwowane z folderu wtyczki, a nie z CDN. Jedyne dane, które Nudge przechowuje, to dwie opcje WordPress w Twojej bazie danych (`nudge_settings` dla konfiguracji i `nudge_db_version` dla aktualizacji), obie usuwane po skasowaniu wtyczki.

== Translations ==

Plogins Nudge zawiera polskie, niemieckie i hiszpańskie tłumaczenie interfejsu wtyczki. Domena tekstowa to `plogins-nudge`, dzięki czemu paczki językowe z WordPress.org mogą również nadpisywać lub rozszerzać dołączone tłumaczenia.

== Changelog ==

= 1.0.2 =
* Dodano dołączone polskie, niemieckie i hiszpańskie tłumaczenia interfejsu wtyczki.

= 1.0.1 =
* Pierwsza stabilna wersja.

= 0.1.4 =
* Zmieniono nazwę na Plogins Nudge for WooCommerce, aby uzyskać bardziej charakterystyczną nazwę wtyczki.

= 0.1.3 =
* Akcja `nudge/bar_rendered` i atrybut `data-nudge-placement` na potrzeby sygnałów analitycznych PRO.

= 0.1.2 =
* Filtr `nudge/bar_context`, aby PRO mógł dostosować postęp, komunikaty i znaczniki poziomów.

= 0.1.1 =
* Filtr `nudge/threshold` i `ThresholdResolver::zoneThreshold()` na potrzeby celów PRO dla stref.

= 0.1.0 =
* Pierwsze wydanie: pasek postępu darmowej wysyłki dla koszyka i kasy, z automatycznym lub ręcznym progiem, aktualizacjami na żywo wraz ze zmianami koszyka, edytowalnymi komunikatami, obsługą trybu ciemnego i ograniczonego ruchu oraz ekranem ustawień w WooCommerce → Nudge.
