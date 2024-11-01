<?php if( ! class_exists( 'action' ) ) 
{
     class action 
     {
          
          public function __construct () 
          {
              parent::__construct();
          }
          
          // actions search 
          
          public static function search ( $posts ) 
          {
            
              $html = null;
              $counts = null;
              
              if ( empty( $posts->value ) ) 
              {
                   $html .= '<p class="error">' . __( "No results found : empty?", 'wp-forum' ) . '</p>'; 
              }
              
              $html .= '<ul class="user-manager__list">';
              
              if ( is_object( $posts ) AND isset( $posts->action ) AND !empty( $posts->value ) ) 
              { 
                   $counts= false; 
                   $users = db::query( 'users', 'normal' );

                   foreach( $users as $key => $users_res ) :
                   
                   $user_info = get_userdata( $users_res->ID );
                   $filter_int= !in_array( $users_res->ID, array( $profile_id ) );
                   $strposts  = strpos( strtolower( $user_info->data->user_login ), $posts->value );
                   
                   $comments  = users::counts_comments( $users_res->ID );
                   $datas     = users::data_comments( $users_res->ID );
                   
                   if ( $strposts === false ) 
                   {    
                        $keys_counts[] = array( 'no-results' => true );
                        
                   } else {
                        
                        $counts[] = true;
                        
                        $html .= '<li class="item">';
                        
                        $html .= '<div class="field label-id">';
                        $html .= '<a href="' . __( site_url( '/wp-admin/user-edit.php?user_id=' . intval( $users_res->ID ) ), 'wp-forum' ) . '" class="id-link">' . __( $users_res->ID, 'wp-forum' ) . '</a>';
                        $html .= '</div>';
                        
                        $html .= '<div class="field label-thumb">';
                            $html .= '<div class="thumb-inner">';
                            
                            // __( user::gravatar( $user_info->data->user_email ), 'wp-forum' ) . 
                             
                            $profiles_vals = users::users_profile( $users_res->ID );
    
                            if( isset( $profiles_vals ) and count( $profiles_vals ) != 0  ) 
                            {
                                $html .= __( "<img class='avatar avatar-96 photo' width='96' height='96' src='{$profiles_vals[0]}' alt=''>", 'wp-forum' ); 
                            } else {
                                $html .= __( user::gravatar( $user_info->data->user_email ), 'wp-forum' ); 
                            } 
                            
                            $html .= '</div>';
                        $html .= '</div>';
                        
                        $html .= '<div class="field label-login">' . __( $users_res->user_login, 'wp-forum' ) . '</div>';
                        $html .= '<div class="field label-email">' . __( $users_res->user_email, 'wp-forum' ) . '</div>';
                        
                        $html .= '<div class="field label-comments">';
                        
                        if( isset( $datas[0] ) ) 
                        {
                               foreach( $datas[0] as $datas_keys => $datas_res ) :
                                     
                                     $comment_ID      = $datas_res->comment_ID;
                                     $post_ID_links   = get_permalink( $datas_res->comment_post_ID );
                                     $comment_content = $datas_res->comment_content;
                                     $comment_date    = $datas_res->comment_date_gmt;
                                     
                                     $html .= "<div class='comments-contents comments-{$comment_ID}'><a href='{$post_ID_links}' title='Post ID - {$datas_res->comment_post_ID}' class='comments-post__id'></a> {$comment_content}</div>";
    
                               endforeach;
                        }
                        
                        $html .= '</div>';
                        
                        $html .= '<div class="field label-action">';
                        $html .= '<a href="#comments" class="users__comments"><span>' . __( $comments['post_count'] ? $comments['post_count'] : $comments['comment_approved'], 'wp-forum' ) . '</span></a>';
                        $html .= '<a href="#replys" class="users__replys"><span>' . __( $comments['post_total'] ? $comments['post_total'] : 0, 'wp-forum' ) . '</span></a>';
                        $html .= '<a href="#likes" class="users__likes"><span>' . __( '0', 'wp-forum' ) . '</span></a>';
                        $html .= '<a href="#shares" class="users__shares"><span>' . __( '0', 'wp-forum' ) . '</span></a>';
                        $html .= '</div>';
                        
                        $html .= '</li>'; 
                        
                   }
                   
                   endforeach;
 
              }
              
              $html .= '</ul>';
              
              if( $counts == false AND !is_null( $counts ) ) 
              {
                  $html .= '<p class="error results-invalid">' . __( "No results found : {$posts->value}", 'wp-forum' ) . '</p>'; 
              } 
                   
              echo $html;
          }
          
          public static function clear () 
          {
              // default results
              $html = null;
              
              $users = db::query( 'users', 'normal' );

              $html .= '<ul class="user-manager__list">';
              
              if( $users ) 
              {
                   foreach( $users as $key => $users_res ) :
                        
                        $user_info = get_userdata( $users_res->ID );
                        $role      = $user_info->roles[0];
                        
                        $comments  = users::counts_comments( $users_res->ID );
                        $datas     = users::data_comments( $users_res->ID );
                        
                        // if( $role != 'administrator' ) :
                        
                        $html .= '<li class="item">';
                        
                        $html .= '<div class="field label-id">';
                        $html .= '<a href="' . __( site_url( '/wp-admin/user-edit.php?user_id=' . intval( $users_res->ID ) ), 'wp-forum' ) . '" class="id-link">' . __( $users_res->ID, 'wp-forum' ) . '</a>';
                        $html .= '</div>';
                        
                        $html .= '<div class="field label-thumb">';
                            $html .= '<div class="thumb-inner">';
    
                                // __( user::gravatar( $user_info->data->user_email ), 'wp-forum' );
                                
                                $profiles_vals = users::users_profile( $users_res->ID );
        
                                if( isset( $profiles_vals ) and count( $profiles_vals ) != 0  ) 
                                {
                                    $html .= __( "<img class='avatar avatar-96 photo' width='96' height='96' src='{$profiles_vals[0]}' alt=''>", 'wp-forum' ); 
                                } else {
                                    $html .= __( user::gravatar( $user_info->data->user_email ), 'wp-forum' ); 
                                } 
                             
                            $html .= '</div>';
                        $html .= '</div>';
                        
                        $html .= '<div class="field label-login">' . __( $users_res->user_login, 'wp-forum' ) . '</div>';
                        $html .= '<div class="field label-email">' . __( $users_res->user_email, 'wp-forum' ) . '</div>';
                        
                        
                        $html .= '<div class="field label-comments">';
                        
                        if( isset( $datas[0] ) ) 
                        {
                               foreach( $datas[0] as $datas_keys => $datas_res ) :
                                     
                                     $comment_ID      = $datas_res->comment_ID;
                                     $post_ID_links   = get_permalink( $datas_res->comment_post_ID );
                                     $comment_content = $datas_res->comment_content;
                                     $comment_date    = $datas_res->comment_date_gmt;
                                     
                                     $html .= "<div class='comments-contents comments-{$comment_ID}'><a href='{$post_ID_links}' title='Post ID - {$datas_res->comment_post_ID}' class='comments-post__id'></a> {$comment_content}</div>";
    
                               endforeach;
                        }
                        
                        $html .= '</div>';
                          
                        $html .= '<div class="field label-action">';
                        $html .= '<a href="#comments" class="users__comments"><span>' . __( $comments['post_count'] ? $comments['post_count'] : $comments['comment_approved'], 'wp-forum' ) . '</span></a>';
                        $html .= '<a href="#replys" class="users__replys"><span>' . __( $comments['post_total'] ? $comments['post_total'] : 0, 'wp-forum' ) . '</span></a>';
                        $html .= '<a href="#likes" class="users__likes comments-hidden__none"><span>' . __( '0', 'wp-forum' ) . '</span></a>';
                        $html .= '<a href="#shares" class="users__shares comments-hidden__none"><span>' . __( '0', 'wp-forum' ) . '</span></a>';
                        $html .= '</div>';
                        
                        $html .= '</li>'; 
                        
                        // endif;
                       
                   endforeach;
              }

              $html .= '</ul>';
              
              echo $html;
              
          }
          
          // insert settings
          
          public static function insert ( $posts ) 
          {
              if( isset( $posts->action ) ) 
              {
                  $vals = $posts->value;
                  if( is_array( $vals ) ) 
                  {
                      $options = unserialize( get_option( 'wpforum_settings_options' ) );
                      
                      if( !empty( $options ) and is_array( $options ) ) 
                      {
                          update_option( 'wpforum_settings_options', serialize( $vals ) );
                      } else {
                          add_option( 'wpforum_settings_options', serialize( $vals ), null, 'yes' );
                      }
                      
                  }
              }
          }
          
          // insert profile
          
          public static function insert_profile ( $posts ) 
          {
              global $wpdb;
              
              $table_name = $wpdb->prefix . ( 'wpforum_profile' );
              
              if( isset( $posts->action ) ) 
              {
                  $vals = $posts->value;
                  
                  $roles = wp_get_current_user();
                  $roles_id = $roles->data->ID; 
                  
                  $values = serialize( array( $roles_id => $vals ) );

                  if ( !empty( $values ) ) 
                  { 
                       $wpdb->insert (
                        
                        	$table_name, 
                        	
                            array( 
                        		'name' => $roles->data->user_login, 
                        		'text' => $values 
                        	), 
                        	
                            array( 
                        		'%s', 
                        		'%s' 
                        	) 
                        );
                  }
              }
          }
          
          public static function insert_posts( $posts ) 
          {
              $html = null;
              
              if( isset( $posts->action ) and 
                  is_array( $posts->value ) && isset( $posts->value ) ) 
              {
              
                  $fields = array( 'post_title' => $posts->value[0], 'post_type' => 'post', 'post_content' => $posts->value[1] );
 
                  if ( !isset( $ids ) ) 
                  {         
                        $ids = wp_insert_post( $fields );
  
                        if ( $ids ) 
                        {
                            // insert post meta
                            
                            $edits = get_edit_post_link( $ids );
                            
                            $html .= "<div class='post-contents item-none'><a href='{$edits}'></a>{$posts->value[0]}</div>";
                            
                        }
                  }
              
              }
              
              echo $html;
          }
          
          
          
     }
}
?>