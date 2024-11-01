<?php if( !class_exists( 'user' ) )
{
     class user {
         
         public static function gravatar ( $email = null ) 
         {
               $html = null;
               $elements = 'class="avatar avatar-50 photo avatar-default" height="50" width="50"';
               
               if ( function_exists( 'get_avatar' ) AND !is_null( $email ) ) 
               {
                  $html .= get_avatar( $email );
               
               } else {
                  
                  $grav_url = 'http://www.gravatar.com/avatar/' . md5( strtolower( $email ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
                  
                  $html .= "<img src='{$grav_url}' {$elements} />";
                  
               }
               
               return $html;
         }
          
     }    
}
?>