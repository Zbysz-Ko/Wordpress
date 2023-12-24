** Zawartosc
Skrypt kliencki Sblam!
	 http://sblam.com/
	 (sblamtest.php, sblam.js.php; wersja 1.3)
Plugin Sblam Spam - integruje Sblam! z WordPress
	 http://blog.koloda.pl/sblam-spam.html
	 (sblam.wp.php; wersja 0.9)

** Instalacja

UWAGA!
- Jeżeli aktualizujesz wtyczkę z wersji starszej niż 0.7, usuń w pliku szablonu comments.php linijkę kodu:
	<script src="wp-content/plugins/sblam/sblam.js.php" type="text/javascript"></script>
- Jeżeli aktualizujesz wtyczkę z wersji starszej niż 0.8 i używałeś konta API, otwórz plik wtyczki sblam.wp.php i skopiuj swój klucz API

1. Pobieramy plugin wraz klientem Sblam! http://blog.koloda.pl/uploads/files/sblam.zip
2. Zawartość rozpakowujemy do katalogu z pluginami WP (wp-content/plugins)
3. Aktywuj plugin w panelu administracyjnym
4. Podaj (lub wygeneruj nowy klucz API: http://sblam.com/key.html)  w opcjach (Panel administracyjny > Opcje > Sblam Spam)
5. Opcjonalnie zainstaluj wtyczkę do podglądu i usuwania spamu z bazy danych: http://bueltge.de/wp-spamviewer-zum-loeschen-und-retten-von-spam/255/


** Test działania
Dodaj na blogu komentarz o treści: to jest test spamu - powinien on zostać dodany do moderacji

** Dodatkowe ustawienia - zajrzyj do sblam.wp.php

** Funkcje szablonu
sblam() - wyświetla ilość spamu wyłapanego przez wtyczkę, funkcja z argumentem true zwraca samą wartość.
Zastosowanie: wklej w szablonie (np. w pliku footer.php): <?php sblam(); ?>