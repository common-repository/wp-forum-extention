<?php if( !class_exists( 'db' ) ) 
{
    
     class db 
     {
          
          public function __construct()
          {
               parent::__construct();
          }
          
         /**
           * wpdb query function 
           * @param name (string)
           * @param keyword (true or false)
           * @param where (string)
           * @param sort (true or false)
           */ 
          
          public static function query( $tbl=null,$is_get=true,$is_where='',$is_sort=true )
          {
               global $wpdb;
               
               if( !is_null( $tbl ) )
               {
                   $tbl_val    = $wpdb->prefix . $tbl; 
                   $tbl_active = true;
               } else {
                   $tbl_val    = $wpdb->prefix;
                   $tbl_active = false;
               }
               
               $is_sort_val  = $is_sort == true ? "ORDER BY `sort` ASC" : $sort = '';
               $is_where_val = is_string( $is_where ) ? $is_where : '';
               
               if( $tbl_active == true ) 
               { 
                   if( $is_get == true  ){
                       $sql = $wpdb->get_results( "SELECT * FROM {$tbl_val} {$is_where_val} {$is_sort_val}", OBJECT );
                       
                   } else {
                       if( $is_get == false ){
                           $sql = $wpdb->get_row( "SELECT * FROM {$tbl_val} {$is_where_val}", OBJECT );
                       }
                   }  
                   
                   if( $is_get == 'normal' ) :
                       $sql = $wpdb->get_results( "SELECT * FROM {$tbl_val}", OBJECT );
                   endif;
               } 

               if( is_array( $sql ) OR is_object( $sql ) ) 
               {
                   return $sql;
               } 
            
          }
          
          public static function insert () 
          {
               global $wpdb;
          } 
          
          public static function update () 
          {
               global $wpdb; 
          } 
          
          public static function delete () 
          {
               global $wpdb;
          } 
          
          public static function posts( $ids=null ) 
          {  
              global $wpdb;
              
              if( !is_null( $ids ) ) 
              {
                   $sql = $wpdb->get_results( "SELECT * FROM {$wpdb->posts} WHERE $wpdb->posts.post_type = 'post' AND $wpdb->posts.post_author = '{$ids}'", OBJECT );
              }
              
              if( is_array( $sql ) OR is_object( $sql ) ) 
              {
                   return $sql;
              } 
          }
         
     }     
             
}