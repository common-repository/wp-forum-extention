<?php if( !class_exists('input')){
    
     class input{
          
          public static $length = array( 100, 200, 500, 1000, 2000, 5000, 10 );
          
          // form open
          public static function form_open($method=array()){
             $method_val=null;
              if( is_array($method)) 
                  
                  if( count($method)>=1){ 
                      foreach($method as $method_key => $method_var){
                           $method_val .= $method_key.'='.$method[$method_key]; 
                      } 
                  }
                  
                  return "<form " .$method_val.">";
          }  
          
          // form close
          public static function form_close($is_action=true){
              if( $is_action==true) return "</form>";
          }
          
          /**
           - input html field
           - object array function
           - input::text(array()) 
          **/
          
          // input(text)
          public static function text($input=array()){
              $html = null; 
              $input_res = null; 
              
              $end_length = end(self::$length);
              
              if( is_array($input)){
                  if( count( $input)>=1){

                      foreach($input as $input_key => $input_var ){
                          $key_value = $input[$input_key]; 
                          if( !empty($input[$input_key])) $input_res .= $input_key."='".($key_value) . "' ";
                      }

                      $html .= "<input type='text' ".__( $input_res ). " />";
                      
                  }
              }
              
              return $html;
              
          }
          
          // input(hidden)
          public static function hidden($input=array()){
              $html = null; 
              $input_res = null; 
              
              $end_length = end(self::$length);
              
              if( is_array($input)){
                  if( count( $input)>=1){

                      foreach($input as $input_key => $input_var ){
                          $key_value = $input[$input_key]; 
                          if( !empty($input[$input_key])) $input_res .= $input_key."='".($key_value) . "' ";
                      }

                      $html .= "<input type='hidden' ".__( $input_res ). " />";
                      
                  }
              }
              
              return $html;
              
          }
          
          
          // input(submit)
          public static function submit($input=array()){
              $html = null;
              $input_res = null;
              $input_class = null;
              if( is_array($input)){
                  if( count( $input)>=1){
                      
                      foreach($input as $input_key => $input_var ){
                           if( !empty($input[$input_key])) 
                           
                           if( $input_key == "class" ){
                               $input_class .= $input_key."='".($input[$input_key]) . " button button-primary' ";
                           }
                           
                           if( $input_key != "class" ){
                               $input_res .= $input_key."='".($input[$input_key]) . "' ";
                           }
                      }

                      $html .= "<input type='submit' ".__( $input_res ) . $input_class . " />";
                  }
              }
              
              return $html;
          }
          
          public static function custom_submit($input=array()){
              $html = null;
              $input_class = null;
              $input_res = null;
              if( is_array($input)){
                  if( count( $input)>=1){
                      
                      foreach($input as $input_key => $input_var ){
                           if( !empty($input[$input_key])) 
                           
                           if( $input_key == "class" ){
                               $input_class .= $input_key."='".($input[$input_key]) . " button' ";
                           }
                           
                           if( $input_key != "class" ){
                               $input_res .= $input_key."='".($input[$input_key]) . "' ";
                           }
                      }

                      $html .= "<input type='submit' ".__( $input_res ) . $input_class . " />";
                  }
              }
              
              return $html;
          }    
          
          // input(password)
          public static function password($input=array()){
              $html = null;
              
              if( is_array($input)){
                  if( count( $input)>=1){
                      
                      foreach($input as $input_key => $input_var ){
                           if( !empty($input[$input_key])) $input_res .= $input_key."='".($input[$input_key]) . "' ";
                      }

                      $html .= "<input type='password' ".__( $input_res )." />";
                  }
              }
              
              return $html;
          }
          
          // input(checkbox)
          public static function checkbox($input=array()){
              $html = null;
              $input_res = null;
              if( is_array($input)){
                  if( count( $input)>=1){
                      
                      foreach($input as $input_key => $input_var ){
                           if( !empty($input[$input_key])) $input_res .= $input_key."=".($input[$input_key]). " ";
                      }

                      $html .= "<input type='checkbox' ".__( $input_res )." />";
                  }
              }
              
              return $html;
          }
          
          // input(radio)
          public static function radio($input=array()){
              $html = null;
              $input_res = null;
              if( is_array($input)){
                  if( count( $input)>=1){
                      
                      foreach($input as $input_key => $input_var ){
                           if( !empty($input[$input_key])) $input_res .= $input_key."=".($input[$input_key]). " ";
                           
                      }

                      $html .= "<input type='radio' ".__( $input_res )." />";
                  }
              }
              
              return $html;
          }
          
          /**
           - input post object 
           - object array function
           - input::post()
          **/
          
          public static function post_is_object () 
          {
              if( count( $_POST ) >=1 ) return ( ( object ) $_POST );
          }
          
          public static function get_is_object () 
          {
              if( count( $_GET ) >=1 ) return ( ( object )$_GET );
          }
          
          public static function post_is_array () 
          {
              if( count( $_POST ) >=1 ) return ( ( array )$_POST );
          }
          
          public static function get_is_array () 
          {
              if( count( $_GET ) >=1 ) return ( ( array ) $_GET );
          }
     
     } 
}