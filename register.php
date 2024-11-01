<?php if( !function_exists( 'reqister_wp_forum' ) or wp_die( 'register directory function call not exists' ) ) {

    function reqister_wp_forum () {
        
        include_once ( 'config/load.php' );
            
        $php     = array( ".php", "/" );
        $php_val = array_shift(array_values($php));
        $slashes = end($php);
        
        // config system (directory)   
        
        $system = $system_load;
        define('WP_MVC_SYSTEMS', 'system');
        if( is_array( $system_load ) ) 
        {
            foreach ( $system as $system_key => $system_var ) {
               if( is_string( $system[$system_key] )){  
                   require_once ( WP_MVC_SYSTEMS . $slashes . $system[$system_key] . $php_val );
               }
            }
        }
         
        // config loader (directory)
        
        $config = $config_load;
        define('WP_MVC_CONFIG', 'config');
        if( is_array( $config ) ) 
        {
            foreach ( $config as $config_key => $config_var ) {
               if( is_string(  $config[$config_key] )){ 
                   require_once ( WP_MVC_CONFIG . $slashes . $config[$config_key] . $php_val );
               }
            }
        }
        
        // config model (directory)
       
        $model = $model_load;
        define('WP_MVC_MODEL', 'model');
        if( is_array( $model ) ) { 
            foreach ( $model as $model_key => $model_var ) {
               if( is_string(  $model[$model_key] )){ 
                   require_once ( WP_MVC_MODEL . $slashes . $model[$model_key] . $php_val );
               }
            }
        }
        
        // config control (directory)
    
        $control = $control_load;
        define('WP_MVC_CONTROLLER', 'controller');
        if( is_array( $control ) ) {
            foreach ( $control as $control_key => $control_var ) {
               if( is_string(  $control[$control_key] )){ 
                   require_once ( WP_MVC_CONTROLLER . $slashes . $control[$control_key] . $php_val );
               }
            }
        }
            
    }
    
}
?>