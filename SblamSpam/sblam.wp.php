<?php
/*
Plugin Name: Sblam Spam
Plugin URI: http://blog.koloda.pl/sblam-spam.html
Description: Plugin do blokowania spamu w komentarzach wykorzystujący filtr antyspamowy Sblam! ( http://sblam.com/ ) Kornela Lesińskiego
Version: 0.9
Author: WaLLacE
Author URI: http://wallace.kom.pl/
*/

/* Instalacja, aktualizacja, zasada działania, opis funkcji: http://blog.koloda.pl/sblam-spam.html */

define('SBLAM_VERSION', '0.9' );

class SBLAM {
	
	private $commentdata;
	private $postset = array( NULL, NULL, NULL, NULL );
	
	function __construct( $commentdata ) {
		$this->commentdata = $commentdata;
	}

	private function sblam_check( $approved ) {

		// Nie sprawdzamy jezeli WP juz nie lubi komentarza
		if( $approved != 1 )
			return $approved;

		if( $this->commentdata['comment_type'] == 'trackback' || $this->commentdata['comment_type'] == 'pingback' ) {
			if( get_option('sblam_option_pingback') )
				return $approved;
			return '0';
		}
		
		if( !empty($this->commentdata['comment_content']) ) {
			$_POST['comment'] = $this->commentdata['comment_content'];
			$this->postset[0] = 'comment';
		}
		if( !empty($this->commentdata['comment_author']) ) {
			$_POST['author'] = $this->commentdata['comment_author'];
			$this->postset[1] = 'author';
		}
		if( !empty($this->commentdata['comment_author_email']) ) {
			$_POST['email'] = $this->commentdata['comment_author_email'];
			$this->postset[2] = 'email';
		}
		if( !empty($this->commentdata['comment_author_url']) ) {
			$_POST['url'] = $this->commentdata['comment_author_url'];
			$this->postset[3] = 'url';
		}
		
		require_once dirname(__FILE__).'/sblamtest.php';

		switch ( sblamtestpost( $this->postset, ((bool)get_option('sblam_option_apikey')?get_option('sblam_option_apikey'):NULL) ) ):
			case 2:
				update_option('sblam_count', get_option('sblam_count')+1);
				return 'spam';
			case -2:
			case -1:
				return '1';
			case 0:
				$msg = 'Dodany niesprawdzony komentarz - błąd filtru antyspamowego:'."\r\n\r\n".sblamlasterror()."\r\n\r\n";
				$msg .= 'Sprawdź ostatnio dodane komentarze: '.get_option('siteurl').'/wp-admin/edit-comments.php'."\r\n";
				$msg .= 'Zachowanie systemu możesz ustawić w opcjach wtyczki.'."\r\n";
				@wp_mail( get_bloginfo('admin_email'), '['.get_option('blogname').'] Dodano niesprawdzony komentarz', $msg );
				if( get_option('sblam_option_onerror') )
					return '1';
				return '0';
			case 1:
				update_option('sblam_count', get_option('sblam_count')+1);
			default:
				return '0';
		endswitch;
		
		return 0;
	
	}

	static function init( $commentdata ) {
		global $sblam;
		$sblam = new SBLAM( $commentdata );
		return $commentdata;
	}

	static function check( $approved ) {
		global $sblam;
		if( isset($sblam) && is_object($sblam) && $sblam instanceof SBLAM )
			return $sblam->sblam_check( $approved );
		else {
			$msg = 'Autor wtyczki Sblam Spam! coś nawalił. Proszę poinformuj go o tym mailu.';
			@wp_mail( get_bloginfo('admin_email'), '['.get_option('blogname').'] Dodano niesprawdzony komentarz', $msg );
			return $approved;
		}
	}

	function register( $errors ) { //TODO
		/* if( empty($errors) ) {
			array('mode'=>'register');
		}
		return $errors; */
	}
	
	static function add_js() {
		echo '<!-- Spam blokuje Sblam! http://sblam.com/ -->'."\n";
		echo '<script src="'.get_option('siteurl').'/'.strstr( dirname(__FILE__), PLUGINDIR ).'/sblam.js.php" type="text/javascript"></script>'."\n";
	}

	static function add_admin_options_page() {
		add_options_page('Sblam Spam', 'Sblam Spam', 8, basename(__FILE__), array('SBLAM', 'admin_options_page') );
	}
	
	static function admin_options_page() {
		if ( isset( $_POST['sblam_options_update'] ) ) {
			// TODO: preg_replace('/[^[:alnum:]]/', '', $_POST['sblam_option_apikey']);
			update_option('sblam_option_apikey',$_POST['sblam_option_apikey']);
			update_option('sblam_option_pingback',intval(isset($_POST['sblam_option_pingback'])));
			update_option('sblam_option_onerror',intval(isset($_POST['sblam_option_onerror'])));
		} 
		?>
		<div class="wrap">
			<h2>Opcje Sblam!</h2>
			<p><?php SBLAM::count_spam(); ?></p>
			<form method="post">
				<fieldset>
					<input type="hidden" name="sblam_options_update" value="1"/>
					<p>Klucz API (<a href="http://sblam.com/key.html">wygeneruj nowy</a>): <input type="text" size="20" name="sblam_option_apikey" value="<?php echo get_option('sblam_option_apikey'); ?>" /></p>
					<p>
						<input type="checkbox" name="sblam_option_pingback" value="1" <?php if (get_option('sblam_option_pingback')) { ?>checked="checked"<?php } ?>>
						Nie przenoś nadchodzących sygnałów Trackback/Pingback do moderacji.
					</p>
					<p>
						<input type="checkbox" name="sblam_option_onerror" value="1" <?php if (get_option('sblam_option_onerror')) { ?>checked="checked"<?php } ?>>
						Dodaj nowe komentarze, jeżeli filtr antyspamowy zawiedzie (odznaczone: komentarze przenoszone do moderacji).
					</p>
					<input type="submit" name="Submit" value="<?php _e('Update Options') ?> &raquo;" />
				</fieldset>	
			</form>
			<p>Wersja:<?php echo SBLAM_VERSION; ?></p>
		</div>
		<?php
	}

	static function count_spam( $simple = false ) {
		if( $simple )
			return get_option('sblam_count');
		echo '<a href="http://sblam.com/" title="Dobry filtr antyspamowy dla Twojej strony!">Sblam!</a> pożarł już <strong>'.get_option('sblam_count').'</strong> sztuk spamu i wciąż mu mało!';
	}

	static function install() {
		if( !get_option('sblam_count') )
			add_option('sblam_count', 0);
	}
}


// zwraca ile zlapano spamu
function sblam( $simple = false ) {
	return SBLAM::count_spam( $simple );
}
// kompatybilnosc ze wczsniejszymi wersjami
function sblam_count( $simple = false ) {
	return sblam( $simple );
}

add_filter('preprocess_comment', array('SBLAM', 'init') );
add_filter('pre_comment_approved', array( 'SBLAM', 'check') );
add_action('comment_form', array('SBLAM', 'add_js') );
add_action('admin_menu', array('SBLAM', 'add_admin_options_page') );
add_action('activate_sblam/sblam.wp.php', array('SBLAM', 'install') );
//TODO add_filter('registration_errors', 'sblam_register');
//TODO if OPENID: var js not set
?>