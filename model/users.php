<?php if( !class_exists( 'users' ) ){
    
    class users extends db
    {
          
          public static $label = 'users';
          
          public function __construct() 
          {
               global $wpdb;
               parent::__construct();
          }
          
          /**
           *  first result object array() 
           *  return string()
           *  count user
           *  @param null (empty)(0)
          **/
          
          public static function counts () 
          {
                global $wpdb;
                
                $counts = array();
                $lables = $wpdb->prefix . self::$label;
                $sql    = $wpdb->get_results( "SELECT * FROM {$lables}", OBJECT );
                
                if( is_array( $sql ) AND $sql ) 
                {
                    foreach( $sql as $sqls ) :
                         
                         $infos = get_userdata( $sqls->ID );
                         $filters = in_array( $infos->roles[0], array( 'forum_role' ) );
                         
                         if( $infos ) 
                         {   
                             $counts[] = __( $sqls, 'wp-forum' );
                         }

                    endforeach;
                }
                
                return count( $counts );
              
          }
          
          /**
           *  first result string array() 
           *  return string()
           *  count comments for user
           *  @param int (user_id)
          **/
          
          public static function counts_comments ( $ids = null ) 
          {
                global $wpdb;
                
                $users_counts = array();
                $where = "WHERE user_id = {$ids}";
                
                if( !is_null( $ids ) AND intval( $ids ) ) :
                
                    $comment_counts = ( array ) $wpdb->get_results( "
                    		SELECT user_id, COUNT( * ) AS total
                    		FROM {$wpdb->comments}
                    		{$where}
                    		GROUP BY user_id
                            ORDER BY total
                    	", object );
                        
                    foreach ( $comment_counts as $comment_keys => $count ) 
                    {
                          
                          $user       = get_userdata( $count->user_id ) ;
                          $post_count = get_usernumposts( $user->ID );
                          $comments   = get_comments( array( 'user_id' => $count->user_id ) );

                          $users_counts = array( 
                                                    'display_name'     => $user->display_name,
                                                    'post_count'       => $post_count,
                                                    'post_total'       => $count->total,
                                                    'comment_approved' => $comments[$comment_keys]->comment_approved,
                                                    
                                                );
                    }
                
                endif;
                
                if( empty( $users_counts ) ) {
                    
                    $counts = array( 
                                        'display_name'     => null,
                                        'post_count'       => 0,
                                        'post_total'       => 0,
                                        'comment_approved' => 0,
                                   );
                    
                } else {
                    
                    $counts = $users_counts;
                    
                }

                return $counts; 
          } 
          
          /**
           *  first result string array() 
           *  return string()
           *  data comments for user
           *  @param int (user_id)
          **/
          
          public static function data_comments ( $ids = null ) 
          {
                global $wpdb;
                
                $comments = array();
                $where = "WHERE user_id = {$ids}";
                
                if( !is_null( $ids ) AND intval( $ids ) ) :
                
                    $comment_counts = ( array ) $wpdb->get_results( "
                    		SELECT user_id, COUNT( * ) AS total
                    		FROM {$wpdb->comments}
                    		{$where}
                    		GROUP BY user_id
                            ORDER BY total
                    	", object );
                        
                    foreach ( $comment_counts as $comment_keys => $counts ) 
                    {
                          $comments[] = get_comments( array( 'user_id' => $counts->user_id ) );
                    }
                
                endif;
                
                return $comments; 
          }
          
          /**
           *  first result string array() 
           *  return string()
           *  count comments for user
           *  @param int (user_id)
          **/
          
          public static function accounts_comments ( $ids = null ) 
          {
                global $wpdb;
                
                $users_counts = array();
                $where = 'WHERE comment_approved = 1 AND user_id <> 0';
                
                if( !is_null( $ids ) AND intval( $ids ) ) :
                
                    $comment_counts = ( array ) $wpdb->get_results( "
                    		SELECT user_id, COUNT( * ) AS total
                    		FROM {$wpdb->comments}
                    		{$where}
                    		GROUP BY user_id
                            ORDER BY total
                    	", object );
                        
                    foreach ( $comment_counts as $comment_keys => $count ) 
                    {

                          if( $count->user_id == $ids ) 
                          {
                              
                              $user       = get_userdata( $count->user_id ) ;
                              $post_count = get_usernumposts( $user->ID );
                              
                              $comments   = get_comments( array( 'user_id' => $count->user_id ) );

                              $users_counts = array( 
                                                        'display_name' => $user->display_name,
                                                        'post_count'   => $post_count,
                                                        'post_total'   => $count->total,
                                                        'comment_approved' => $comments[$comment_keys]->comment_approved,
                                                   );
                              
                              
                          } 
                    }
                
                endif;
                
                if( empty( $users_counts ) ) {
                    
                    $counts = array( 
                                        'display_name'     => null,
                                        'post_count'       => 0,
                                        'post_total'       => 0,
                                        'comment_approved' => 0,
                                   );
                    
                } else {
                    
                    $counts = $users_counts;
                    
                }

                return $counts;
          } 
          
          /**
           *  first result string int() 
           *  return int()
           *  count reply for user
           *  @param int (comment_id)
          **/
          
          public static function child_comment_counter ( $id = null )
          {
                global $wpdb;
                
                if( !is_null( $id ) ) 
                {
                    $querys   = "SELECT COUNT(comment_post_id) AS count FROM `wp_comments` WHERE `comment_approved` = 1 AND `comment_parent` = {$id}";
                    $children = $wpdb->get_row( $querys );
                    $counts   = $children->count;
                
                } else {
                    
                    $counts   = 0;
                    
                }
                
                return $counts;
          }
          
          /**
           *  first result string array() 
           *  return int()
           *  count reply for user
           *  @param int (comment_id)
           *  @return users array meta data
          **/
          
          public static function accounts ( $ids = null ) 
          {
                global $wpdb;
                
                $users = array();
                $comments = array();
                
                $where = 'WHERE comment_approved = 1 AND user_id <> 0';
                
                if( !is_null( $ids ) AND intval( $ids ) ) :
                
                    $comment_counts = ( array ) $wpdb->get_results( "
                    		SELECT user_id, COUNT( * ) AS total
                    		FROM {$wpdb->comments}
                    		{$where}
                    		GROUP BY user_id
                            ORDER BY total
                    	", object );
                        
                    foreach ( $comment_counts as $count ) 
                    {

                          if( $count->user_id == $ids ) 
                          {
                              
                              $users[]    = get_userdata( $count->user_id ) ;
                              $comments[] = get_comments( array( 'user_id' => $count->user_id ) );
                              
                          } 
                    }
                
                endif;
                
                $merge = array_merge( $users, $comments );

                return $merge;
          } 
          
          /**
           *  first result string array() 
           *  return array()
           *  @param none ( null )
           *  @return users array meta data
          **/
          
          public static function all_accounts ( $strs = null ) 
          {
                global $wpdb;
                
                $users    = array();
                $comments = array();
                $options  = array( 'merge', 'userdata', 'comments' );
                
                $where = 'WHERE comment_approved = 1 AND user_id <> 0';
                
                if( !is_null( $strs ) ) :
                
                    $comment_counts = ( array ) $wpdb->get_results( "
                    		SELECT user_id, COUNT( * ) AS total
                    		FROM {$wpdb->comments}
                    		{$where}
                    		GROUP BY user_id
                            ORDER BY total
                    	", object );
                        
                    foreach ( $comment_counts as $count ) 
                    {

                          $users[]    = get_userdata( $count->user_id ) ;
                          $comments[] = get_comments( array( 'user_id' => $count->user_id ) );
                    }
                
                endif;
                
                
                if( in_array( $strs,  $options ) && $strs == 'merge' ) 
                {
                    $arrays = array_merge( $users, $comments );
                } else if ( in_array( $strs,  $options ) && $strs == 'userdata' ) {
                    $arrays = $users;
                } else if ( in_array( $strs,  $options ) && $strs == 'comments' ) {
                    $arrays = $comments;
                }

                return $arrays;
          }
          
          /**
           *  first result array() 
           *  return array() ( value )
           *  @param is_numeric ( int )
           *  @return profile array() meta data
          **/
          
          public static function users_profile( $ids = null ) 
          {
               global $wpdb;
               
               $table = $wpdb->prefix . ( 'wpforum_profile' );
               $id = $ids;
               
               if( !is_null( $id ) ) 
               {
                   
                   $vals    = array();
                   $avatars = $wpdb->get_results( "SELECT * FROM {$table}", OBJECT );
                   
                   if( $avatars ) 
                   {
                       foreach( $avatars as $keys => $res ) :
                       
                             $texts = unserialize( $res->text );
                             $texts_keys = key( $texts );
                             
                             if( $texts_keys == $id ) 
                             { 
                                 $vals = array_values( $texts ); 
                             }
                             
                       endforeach;
                   }
               
               }
               
               return $vals;
          }
             
    }
}
?>