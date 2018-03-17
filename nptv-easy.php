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

define ( 'ROOT_PATH', dirname(__FILE__) );

function nptv_control_meta() {
	return array(
		'slug' => 'neopolitica-control',
		'name' => 'NPTV Control',
		'file' => __FILE__,
		'version' => '0.4',
	);
}
#Functions and Widgets
include(ROOT_PATH . '/includes/nptv-functions.php');

///=====================================================///

class NeoPoliticaTV_Control {

	private $plugin_meta;

	private $capability = 'manage_options' ;

	//Constructor
	function __construct($plugin_meta = array()) {

		if( empty( $plugin_meta )) {
			return;
		}

		$this->plugin_meta = $plugin_meta;

		if( !current_user_can( $this->capability )){
			return;
		}

		add_action('admin_menu', array($this, 'nptv_build_menu'));

		add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts'));

        add_action('wp_ajax_nptv_add_new', array( $this, 'ajax_add_post'));

        add_action('wp_ajax_nptv_get_links', array($this, '_get_links'));


	} //End Constructor

    function _get_links() {
        $links = $this->_nptv_get_links();
        $this->_ajax_return($links);
    }


    function ajax_add_post(){
        $url = $_POST['url'];
        $cat_id = $_POST['cat'];
        $tags = $_POST['tags'];
        // if(function_exists('agregar_noticia')){

        //     $this->_ajax_return('SI existe la function');
        // }else{
        //     $this->_ajax_return('No existe la function');
        // }
        // //$this->_ajax_return($url);
        $nptv_nota = agregar_noticia($url, $cat_id, $tags);
        $this->_ajax_return($nptv_nota);

    }

    function _ajax_return( $response = true ){
        wp_send_json(  $response );
        exit;
    }

	function nptv_build_menu(){
		add_menu_page( 
			$this->puglin_meta['name'],
			$this->plugin_meta['name'],
			$this->capability, 
			$this->plugin_meta['slug'],
			array($this,'nptv_render_page'),
			plugins_url('/src/assets/logo_menu.jpg',__FILE__),
			6 );
	}

	function nptv_render_page(){
		if(!current_user_can($this->capability)){
            wp_die('No tienes suficientes provilegios para editar esta pagiba - NPTV Easy');
        }
        ?>
        <!-- Main content -->
        <div id="nptv-easy-main">
            
        </div>
        <?php
	}

	function enqueue_scripts($hook_page){
        if($hook_page == 'jetpack_page_stats'){
            wp_enqueue_script(
                'nptv-stats-script',
                plugins_url('/js/nptv-stats.js',__FILE__),
                array(),
                $this->plugin_meta['version'],
                true
            );
        }

        if('toplevel_page_neopolitica-control' !== $hook_page){
            return;
        }
        // echo NPTV_DIR;
        // echo NPTV_URL;
        $script_handle = $this->plugin_meta['slug'] . '-main';
        wp_enqueue_script( 
            $script_handle,
            plugins_url( '/build/app.js', __FILE__),
            array( ),
            $this->plugin_meta['version'], 
            true 
        );

        wp_enqueue_script( 'NPTV-axios-js', 'https://unpkg.com/axios@0.16.2/dist/axios.min.js', array(), '0.16.2', true );
        wp_enqueue_script( 'NPTV-qs-js', 'https://unpkg.com/qs@6.5.1/dist/qs.js', array(), '6.5.1', true );

        wp_enqueue_style( 
            $script_handle . 'style',
            plugins_url('/theme/index.css',__FILE__), array( ), $this->plugin_meta['version'], 'all' );

        $data = array(
            // 'strings'      => array(
            //     'no_events'    => _x( '(none)', 'no event to show', 'wp-cron-pixie' ),
            //     'due'          => _x( 'due', 'label for when cron event date', 'wp-cron-pixie' ),
            //     'now'          => _x( 'now', 'cron event is due now', 'wp-cron-pixie' ),
            //     'passed'       => _x( 'passed', 'cron event is over due', 'wp-cron-pixie' ),
            //     'weeks_abrv'   => _x( 'w', 'displayed in interval', 'wp-cron-pixie' ),
            //     'days_abrv'    => _x( 'd', 'displayed in interval', 'wp-cron-pixie' ),
            //     'hours_abrv'   => _x( 'h', 'displayed in interval', 'wp-cron-pixie' ),
            //     'minutes_abrv' => _x( 'm', 'displayed in interval', 'wp-cron-pixie' ),
            //     'seconds_abrv' => _x( 's', 'displayed in interval', 'wp-cron-pixie' ),
            //     'run_now'      => _x( 'Run event now.', 'Title for run now icon', 'wp-cron-pixie' ),
            // ),
            'nonce'        => wp_create_nonce( 'nptv-control' ),
            'timer_period' => 5, // How often should display be updated, in seconds.
            'ajax_url'     => admin_url('admin-ajax.php'),
            'data'         => array(
                'categories' => $this->_get_categories(),
            ),
        );
        wp_localize_script( $script_handle, 'NPTV', $data );
    }


    private function _get_categories(){
        $categories_obj = get_categories();
        $categories = array();

        foreach($categories_obj as $pn_cat){

            $categories[$pn_cat->cat_ID] = $pn_cat->cat_name;
        }
        return $categories;

    }

} // End Class



//include_once(ABSPATH . 'wp-includes/pluggable.php');

// class NeoPoliticaTV_Easy 
// {
// 	// private plugin_meta = array(
// 	// 	'slug'    => 'nptv-easy',
// 	// 	'name'    => 'NPTV Easy',
// 	// 	'file'    => __FILE__,
// 	// 	'version' => '0.3',
// 	// )

// 	private static $instance;

// 	function __construct(){
// 		define('NPTV_URL', plugins_url('', __FILE__));
// 		define('NPTV_DIR', dirname(__FILE__));
// 		define('NPTV_NAME', 'NPTV Easy');
// 		define('NPTV_SLUG', 'nptv-easy');
// 		define('NPTV_VERSION','0.3');

// 		include ( NPTV_DIR . '/includes/class-nptv-easy.php');
// 	}

// 	public static function instance(){
// 		if(! isset(self::$instance)){
// 			self::$instance = new self;
// 		}
// 		return self::$instance;
// 	}


// }

// function NPTV() {
// 	return NeoPoliticaTV_Easy::instance();
// }

// $nptv_easy = NPTV();


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

new NeoPoliticaTV_Control( nptv_control_meta() );
 ?>