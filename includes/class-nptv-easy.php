<?php 

class Nptv_Easy {
    // Usado para informacion del plugin

    private $plugin_meta;

    private $capability = 'manage_options' ;

    public function __construct($plugin_meta = array() ){
        if(empty($plugin_meta)){
            return;
        }

        $this->plugin_meta = $plugin_meta;

        // El uso de este plugin esta reservado para Administradores
        if(! current_user_can( $capability )) {
            return ;
        }


        // Agregar pagina del Plugin dentro del Setup

        add_action('admin_menu', array( $this, 'nptv_easy_menu'));
    }

    public function nptv_easy_menu(){
        add_options_page( $this->plugin_meta['name'] . 'Plugin', $this->plugin_meta['name'] , $capability, $this->plugin_meta['slug'], array($this, 'nptv_control_options_page') );
    }

    public function nptv_control_options_page(){
        
    }
}

?>