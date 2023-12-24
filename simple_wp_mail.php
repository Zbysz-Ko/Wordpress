<?php
/*
Plugin Name: Simple WP Mailer
Plugin URI: http://wallace.kom.pl/blog/wordpress-simple-wp-mailer.html
Description: Zamienia standardową funkcję do wysyłania maili (przełącznik -f w sendmailu, tytuły w UTF-8, w polu OD mail admina)
Author: WaLLacE
Version: 0.1
Author URI: http://wallace.kom.pl/
*/

/*  2007 Zbigniew Koloda CC BY-SA 

Ten utwór objęty jest licencją Creative Commons Uznanie autorstwa-Na tych samych warunkach 2.5 Polska.
Aby zobaczyć kopię niniejszej licencji przejdź na stronę http://creativecommons.org/licenses/by-sa/2.5/pl/
lub napisz do Creative Commons, 171 Second Street, Suite 300, San Francisco, California 94105, USA.

This work is licensed under the Creative Commons Attribution-Share Alike 2.5 Poland License.
To view a copy of this license, visit http://creativecommons.org/licenses/by-sa/2.5/pl/
or send a letter to Creative Commons, 171 Second Street, Suite 300, San Francisco, California, 94105, USA.

*/


if ( !function_exists('wp_mail') ) :
function wp_mail($to, $subject, $message, $headers = '') {
	
	$subject = preg_replace('/([^a-z ])/ie', 'sprintf("=%02x",ord(StripSlashes("\\1")))', $subject);
	$subject = str_replace(' ', '_', $subject);
	$subject = "=?utf-8?Q?$subject?=";
	
	if( $headers == '' ) {
		$headers = "MIME-Version: 1.0\r\n".
			"From: ".get_bloginfo('admin_email')."\r\n". 
			"Reply-To: ".get_bloginfo('admin_email')."\r\n".
			"Content-Type: text/plain; charset=utf-8\r\n".
			"User-Agent: WordPress ".get_bloginfo('version')." (Simple WP Mailer)";
	}

	return @mail($to, $subject, $message, $headers, "-f".get_bloginfo('admin_email'));
}
endif;

?>