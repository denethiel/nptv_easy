<?php 

class Nptv_Easy {
    // Usado para informacion del plugin

    private $plugin_meta;

    private $capability = 'manage_options' ;

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



        // Agregar pagina del Plugin dentro del Setup

        
        add_action('admin_menu', array( $this, 'nptv_build_menu'));


        //Agrega el JS Script
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_scripts'));
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

    public function nptv_easy_options_page(){

        echo "Estoy en el constructor del menu";

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
        echo $hook_page;
    }
}

new Nptv_Easy();

?>