<?php
/*
Plugin Name: Sblam Spam
Plugin URI: http://wallace.kom.pl/blog/sblam-spam.html
Description: Plugin do blokowania spamu w komentarzach wykorzystujÄ…cy filtr antyspamowy Sblam! ( http://spam.geekhood.net/ ) Kornela Lesinskiego
Version: 0.5
Author: WaLLacE
Author URI: http://wallace.kom.pl/
*/

// Sblam! Klucz API
// Wiecej: http://spam.geekhood.net/key.html
// Zamiast NULL wpisz miêdzy cudzys³owami wygenerowany swój klucz, aby wygladalo tak:
// define('SBLAM_KEY', 'tu_twoj_klucz' );
define('SBLAM_KEY', NULL );

// Co zrobic z trackbackami/pingbackami?
// true - zostana dodane
// false - laduja w poczekalni (do moderacji)
define('SBLAM_PINGBACK', false );

// W przypadku bledu Sblama, jezeli zmienna ustawiona na:
// true - komentarz zostanie dodany
// false - komentarz wyladuje w poczekalni (do moderacji)
define('SBLAM_ON_ERROR', false );



function sblam_errorHandler( $lvl, $msg ) {
	if( strpos( $msg, 'Sblam') === 0 ) {
		$msg = 'Na Twojej stronie wystapil blad:'."\r\n\r\n".$msg."\r\n\r\n";
		$msg .= 'Komentarze nie sa sprawdzane przez filtr antyspamowy, laduja w poczekalni lub sa akceptowane w zaleznosci od ustawien wtyczki.'."\r\n";
		$msg .= 'Wiadomosc wygenerowana automatycznie przez Twoj blog: ('.get_bloginfo('url').')';
		wp_mail( get_bloginfo('admin_email'), '['.get_option('blogname').'] Sblam: blad filtru antyspamowego', $msg );
		return true;
	}
	return false;
}

function sblam_check( $approved ) {
	
	global $commentdata;
	
	if( $approved != 1 ) return $approved;
	
	if( $commentdata['comment_type'] == 'trackback' || $commentdata['comment_type'] == 'pingback' ) {
		if( SBLAM_PINGBACK ) return '1';
		return '0';
	}
	
	if( is_user_logged_in() ) {
		$sblam_vars = array('comment',NULL,NULL,NULL);
	} else {
		$sblam_vars = array('comment','author','email','url');
	}
	
	set_error_handler('sblam_errorHandler');
	
	include_once 'sblamtest.php';

	$rezultat = sblamtestpost($sblam_vars, SBLAM_KEY );
	
	restore_error_handler();
	
	if (  $rezultat == 2 ) {
		return 'spam';
	} elseif(  $rezultat == 1 || ( !SBLAM_ON_ERROR && $rezultat == 0 ) ) {
		return '0';
	} 
	
	return '1';
} 
 
add_filter('pre_comment_approved', 'sblam_check' );
?>
