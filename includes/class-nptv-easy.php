<?php 

class Nptv_Easy {
    // Usado para informacion del plugin

    private $plugin_meta;

    private $capability = 'manage_options' ;

    private $func;

    public function __construct(){
        
        // if(empty($plugin_meta)){
        //     return;
        // }

        //var_dump(current_user_can($this->capability));

        // $this->plugin_meta = $plugin_meta;

        // El uso de este plugin esta reservado para Administradores

        if( ! current_user_can( $this->capability )) {
            //wp_die('No tienes suficientes provlegios para editar esta pagina');
            return;
        }

        add_action('init', array($this, 'init'));

    }

    function init(){

        include(NPTV_DIR . '/includes/nptv-functions.php');

        this->$func = new nptv_functions();



        // Agregar pagina del Plugin dentro del Setup

        
        add_action('admin_menu', array( $this, 'nptv_build_menu'));


        //Agrega el JS Script
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts'));


        add_action('wp_ajax_nptv_add_post', array($this,'ajax_add_post'));
    }

    public function ajax_add_post(){
        $external_url = $_POST['external_url'];
        $category_id = $_POST['category_id'];
        $tag_name = $_POST['tag_name'];

        $nptv_nota = $this->func->agregar_noticia($external_url, $category_id, $tag_name);
        $this->_ajax_return($nptv_nota);
    }

    

    public function nptv_build_menu(){

        /*
        Usamos la funcion add_options_page
        add_options_page ( $page_title, $menu_title, $capability, $menu-slug, $funcion)
        */


        add_menu_page( 
            NPTV_NAME, 
            NPTV_NAME, 
            $this->capability, 
            NPTV_SLUG, 
            array($this,'nptv_easy_options_page'), 
            plugins_url( 'nptv-easy/src/assets/logo_menu.jpg', NPTV_DIR), 
            6);

        // add_options_page(
        //     $this->plugin_meta['name'] . 'Plugin', 
        //     $this->plugin_meta['name'] , 
        //     'manage_options', 
        //     $this->plugin_meta['slug'], 
        //     array( $this, 'nptv_easy_options_page') 
        // );
    }

    private function _ajax_return( $response = true ){
        echo json_encode( $response );
        exit;
    }

    public function nptv_easy_options_page(){


        if(!current_user_can($this->capability)){
            wp_die('No tienes suficientes provilegios para editar esta pagiba - NPTV Easy');
        }
        ?>
        <!-- Main content -->
        <div id="nptv-easy-main">
            
        </div>
        <?php
    }

    public function enqueue_scripts($hook_page){
        if($hook_page == 'jetpack_page_stats'){
            wp_enqueue_script(
                'nptv-stats-script',
                NPTV_URL . '/js/nptv-stats.js',
                array(),
                NPTV_VERSION,
                true
            );
        }

        if('toplevel_page_nptv-easy' !== $hook_page){
            return;
        }
        // echo NPTV_DIR;
        // echo NPTV_URL;
        $script_handle = NPTV_SLUG . '-main';
        wp_enqueue_script( 
            $script_handle,
            NPTV_URL . '/build/app.js',
            array( ),
            NPTV_VERSION, 
            true 
        );

        wp_enqueue_style( 
            $script_handle . 'style',
            NPTV_URL . '/theme/index.css', array( ), NPTV_VERSION, 'all' );

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
            'nonce'        => wp_create_nonce( 'nptv-easy' ),
            'timer_period' => 5, // How often should display be updated, in seconds.
            'data'         => array(
                'categories' => $this->_get_categories(),
            ),
        );
        wp_localize_script( $script_handle, 'NPTV', $data );
    }

    public function _get_categories(){
        $categories_obj = get_categories();
        $categories = array();

        foreach($categories_obj as $pn_cat){
            $categories[$pn_cat->catID] = $pn_cat->cat_name;
        }
        return $categories;
    }

    
}

new Nptv_Easy();

?>