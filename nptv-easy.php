<?php 
/**
 * @link              https://github.com/denethiel/nptv-easy
 * @package           NPTV Easy
 *
 * @wordpress-plugin
 * Plugin Name:       NPTV Easy
 * Plugin URI:        https://github.com/denethiel/nptv-easy
 * Description:       Un Plugin para hacer mas facil todo.
 * Version:           0.3 Alpha 
 * Author:            Jose David Pacheco Valedo
 * Author URI:        http://www.twitter.com/josepdvahn
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nptv-easy
 * Domain Path:       /languages
 * Network:           False
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

include_once(ABSPATH . 'wp-includes/pluggable.php');

class NeoPoliticaTV_Easy 
{
	// private plugin_meta = array(
	// 	'slug'    => 'nptv-easy',
	// 	'name'    => 'NPTV Easy',
	// 	'file'    => __FILE__,
	// 	'version' => '0.3',
	// )

	private static $instance;

	function __construct(){
		define('NPTV_URL', plugins_url('', __FILE__));
		define('NPTV_DIR', dirname(__FILE__));
		define('NPTV_NAME', 'NPTV Easy');
		define('NPTV_SLUG', 'nptv-easy');
		define('NPTV_VERSION','0.3');

		include ( NPTV_DIR . '/includes/class-nptv-easy.php');
	}

	public static function instance(){
		if(! isset(self::$instance)){
			self::$instance = new self;
		}
		return self::$instance;
	}


}

function NPTV() {
	return NeoPoliticaTV_Easy::instance();
}

$nptv_easy = NPTV();


// // Donde la magia sucede...
// require plugin_dir_path( __FILE__ ) . 'includes/class-nptv-easy.php';

// /**
//  * Inicia el plugin en el hook correcto
//  */

// function nptv_easy_admin_init(){
// 	if(is_admin()){
// 	$nptv_easy = new Nptv_Easy( nptv_easy_meta() );
// 	}
// }



// add_action('admin_init','nptv_easy_admin_init');

 ?>