<?php if( !class_exists( '' ) ){
      
      
      class post{
           
           public static function WPQuery( $arry=array()){
                 
                 if( is_array( $arry ) ){
                     
                     $query = new WP_Query( $arry );
                     
                     if( is_object( $query ) ){
                         
                         $return = true;
                     } else {
                        
                         $return = false;
                        
                     }
                     
                 }
                 
                 return $return;
            
           }
           
      }
         
}
?>