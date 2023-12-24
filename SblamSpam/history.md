Originally published on http://blog.koloda.pl/sblam-spam.html

### Sblam Spam
 * Kategoria: WordPress Tagi: plugin, sblam, spam, wordpress *
 * 1 February 2007 08:46 *
 ** Ostatnia zmiana 29.07.2007 - wersja 0.9, wymaga PHP5 (paczka zawiera skrypt kliencki Sblam! wersja 1.3) - pobierz archiwum zip lub tgz. **

## Uwaga!

Jeżeli aktualizujesz wtyczkę z wersji starszej niż 0.7, usuń w pliku szablonu comments.php odwołanie do pliku sblam.js.php.
Jeżeli aktualizujesz wtyczkę z wersji starszej niż 0.8 i używałeś konta API, otwórz plik wtyczki sblam.wp.php, skopiuj swój klucz API i po instalacji wklej w ustawieniach w panelu administracyjnym.

## Wstęp

Dziś światło dzienne ujrzał, stworzony przez Kornela Leśnińskego, nowy filtr antyspamowy do formularzy na stronach WWW: Sblam!. Więcej informacji o sposobie jego działania znajdziecie na stronie domowej filtru. Tutaj natomiast krótkie info jak go zaaplikować w system blogowy WordPress w postaci pluginu.

## Działanie
W pierwszej kolejności uwzględniane są ustawienia WordPress odnośnie komentarzy (ilość linków, moderacja wszystkich, zakazane słowa). Dopiero po pomyślnym przejściu przez wbudowany system (komentarz nie jest oznaczony jako spam lub do moderacji) jest przekazywany do filtru antyspamowego, który poddaje go czterostopniowej ocenie. Jeżeli jest to pewny spam, zostanie oznaczony jako spam (do WP istnieje wtyczka, przy pomocy której można przeglądać/usuwać komentarze oznaczone jako spam: Spam Viewer). Jeżeli istnieje podejrzenie, że komenatrz jest spamem, zostanie on przesunięty do moderacji. W każdym innym przypadku komentarz zostanie dodany (100% czysty lub prawdopodobnie czysty).

W przypadku wystąpienia błędu podczas sprawdzania komentarza przez filtr, na adres email administratora instalacji WP zostanie wysłana informacja z jego treścią. Co się dzieje w takim przypadku z komentarzem? W opcjach wtyczki (Panel administracyjny - Opcje - Sblam Spam) możemy ustalić czy komentarz zostanie dodany, czy też wyląduje w poczekalni (default).

Trackbacki/pingbacki obecnie przenoszone są do moderacji, aczkolwiek można zmienić w opcjach, aby były dodawane bezpośrednio do listy komentarzy (filtr jak na razie nie jest przystosowany do ich sprawdzania).

## Instalacja

1. Pobieramy plugin wraz klientem Sblam! (zip lub tgz)
2. Zawartość rozpakowujemy do katalogu z pluginami WP (wp-content/plugins)
3. Aktywuj plugin w panelu administracyjnym
4. Podaj (lub wygeneruj nowy klucz API) w opcjach (Panel administracyjny > Opcje > Sblam Spam)
5. Opcjonalnie zainstaluj wtyczkę do podglądu i usuwania spamu z bazy danych

## Test działania
Dodaj komentarz o treści: *to jest test spamu*
Powinien on zostać dodany do moderacji, a w obrębie komentarza pojawi się info o oczekiwaniu na sprawdzenie przez administratora bloga.

## Dodatkowe funkcje

Wstaw w szablonie (np. w pliku footer.php) funkcję:
'''
<?php sblam(); ?>
'''
by wyświetlić ilość zablokowanego spamu.

Funkcja z argumentem *true* zwraca samą wartość.

## ToDo
- [] a może by tak całkiem usuwać pewny spam, w ogóle nie dodawać do bazy?
- [x] przezroczyste dla użytkownika błędy połączeń filtru z “serwerem matką”
- [x] informować zalogowanego użytkownika, jeżeli jego komentarz trafił do moderacji (na pierwszy rzut oka nie da się) - edited: wyprowadzono w ostatnich wersjach WP
- [] sprawdzanie trackbacków/pingbacków
- [] strona opcji, przeglądarka spamów, raportowanie błędnego spamu

## Changelog
* 0.9 
    - kompletna przebudowa kodu - przekazywanie danych komentarza poprzez funkcje wbudowane WP (działa z pluginem OpenID i innymi co “czyszczą” tablicę $_POST)
    - funkcja sblam_count() zastąpiona sblam() (poprzednia na razie działa)
* 0.8 ustawienia przeniesiono do PA
* 0.7.1 brak globalnej zmiennej określającej typ komentarza przy sygnałach pingback powodował błąd filtru - dodano niezależne jej przekazywanie
* 0.7 automatyczne załączanie kodu JavaScript wspomagającego filtr antyspamowy
* 0.6
    - wtyczkę dostosowano do nowej wersji filtru
    - nowa funkcja sblam_count() - zwraca ilość zablokowanego spamu
    - kilka drobiazgów
* 0.5 dodano obsługę kluczy API oraz poprawiono kilka drobiazgów
* 0.4 każdy trackback/pingback był błędnie interpretowany przez filtr jako spam (Sblam obsługuje na razie tylko komentarze), obecnie są one wyłapywane i przekazywane do moderacji; rozróżnianie zalogowanych użytkowników
* 0.3 dodano wychwytywanie błędów filtru
* 0.2 uwzględnia zawartość zmiennej $approved przekazanej z WP
* 0.1 pierwsza wersja
