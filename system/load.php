<?php if( !class_exists('load')){
    
     class load{
          
          public static $load = array( 'view', 'model', 'helper' );
          
          public static $slash = array( "/", ".php" );
          
          public static $str = "";
          
          public static $val = null;
          
          public static $int = 0;
          
          public static function view($name=null, $atts=null){
            
                $view = array_shift(array_values(self::$load));
                $slash_val = array_shift(array_values(self::$slash));
                $php_val = end(self::$slash);
                
                $dir = dirname(dirname(__FILE__));
                
                if( !is_null($name)){
                    if( is_dir($dir)){
                        $file = $dir . $slash_val . $view. $slash_val . __( $name ) . $php_val;
                        if( file_exists($file)){
                            if( !empty($name)): require_once $dir . $slash_val . $view . $slash_val . __( $name ) . $php_val; else: endif;
                        }
                    }
                } 
          } 
          
          // load custom model php file
          public static function model($name=null){
            
                end(self::$load); $helper = prev(self::$load);
                $slash_val = array_shift(array_values(self::$slash));
                $php_val = end(self::$slash);
                
                $dir = dirname(dirname(__FILE__));
                
                if( !is_null($name)){
                    if( is_dir($dir)){
                        $file = $dir . $slash_val . $helper. $slash_val . __( $name ) . $php_val;
                        if( file_exists($file)){ 
                            if( !empty($name)): require_once $dir . $slash_val . $helper . $slash_val . __( $name ) . $php_val; else: endif;
                        }
                    }
                } 
          } 
          
          // load custom helper php file
          public static function helper($name=null){
            
                $helper = end(self::$load);
                $slash_val = array_shift(array_values(self::$slash));
                $php_val = end(self::$slash);
                
                $dir = dirname(dirname(__FILE__));
                
                if( !is_null($name)){
                    if( is_dir($dir)){
                        $file = $dir . $slash_val . $helper . $slash_val . __( $name ) . $php_val;
                        if( file_exists($file)){ 
                            if( !empty($name)): require_once $dir . $slash_val . $helper . $slash_val . __( $name ) . $php_val; else: endif;
                        }
                    }
                } 
          }
          
          // load custom helper php file
          public static function control($name=null){
            
                $control = "controllers";
                $slash_val = array_shift(array_values(self::$slash));
                $php_val = end(self::$slash);
                
                $dir = dirname(dirname(__FILE__));
                
                if( !is_null($name)){
                    if( is_dir($dir)){
                        $file = $dir . $slash_val . $control . $slash_val . __( $name ) . $php_val;
                        if( file_exists($file)){ 
                            if( !empty($name)): require_once $dir . $slash_val . $control . $slash_val . __( $name ) . $php_val; else: endif;
                        }
                    }
                } 
          }
           
     }
     

}

?>
