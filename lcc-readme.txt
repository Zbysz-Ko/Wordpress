*** SETUP INSTRUCTIONS *****
1. Wgraj plik lcc.php do katalogu /wp-content/plugins
2. Aktywuj wtyczke w Panelu Admina
3. Wyedytuj odpowiedni plik theme (np. sidebar.php) dodajac linie:

<?php if (function_exists (lcc_show_comments)) { ?>
<h3>Ostatnie komentarze</h3>
<ul>
<?php lcc_show_comments(); ?>
</ul>
<?php } ?>

*** Opcje ******

Dostepne opcje (default):
1. Ile pokazac komentarzy (10)
2. Po ilu znakach ma obciac komentarz (100)
    Wartosc minimalna 1, komentarz nie jest obcinany dokladnie po tylu znakach, a na pierwszej spacji ktora wystapi po nich.
3. Pokazac temat postu do ktorego nalezy komentarz TRUE lub FALSE (TRUE)
4. Wyglad znacznika przyciecia komentarza ([...]), pokaze sie tylko wtedy, kiedy tresc komentarza zostala przycieta.

Przyklady

1. 5 ostatnich komentarzy z okolo 200 znakami tresci, tematami postow do ktorych nalezy komentarz o raz znacznikiem w postaci trzech kropek:
 
<?php if (function_exists (lcc_show_comments)) { ?>
<h3>Latest Comments</h3>
<ul>
<?php lcc_show_comments( 5, 200, TRUE, '...' ); ?>
</ul>
<?php } ?>

2. 20 ostatnich komentarzy, reszta wartosci standardowe (default):
 
<?php if (function_exists (lcc_show_comments)) { ?>
<h3>Latest Comments</h3>
<ul>
<?php lcc_show_comments( 20 ); ?>
</ul>
<?php } ?>

3. 10 ostatnich komentarzy z okolo 10 znakami tresci i bez pokazywania tematow postow do ktorych naleza komentarze, reszta standardowo (default):
 
<?php if (function_exists (lcc_show_comments)) { ?>
<h3>Latest Comments</h3>
<ul>
<?php lcc_show_comments( 10, 10, FALSE ); ?>
</ul>
<?php } ?>

========== CHANGELOG ======================================
v0.1 - Initial Release