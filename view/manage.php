<?php

/**
 * @author charly
 * @copyright 2016
 */
 
global $wpdb;

$profile      = wp_get_current_user();
$profile_id   = $profile->data->ID;
$profile_role = $profile->roles[0];

echo html::wrap_start( 'wp-forum__wrap' ); 

?>

<h1><?php echo __( 'WP Forum : Manager', 'wp-forum' ); ?></h1>

<div id="results"></div>

<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">
<div id="postbox-container-1" class="postbox-container">	
<div id="normal-sortables" class="meta-box-sortables ui-sortable">

<!-- #################### tab 1 ################### -->
<div id="dashboard_right_now" class="postbox">
<button type="button" class="handlediv button-link" aria-expanded="true">
<span class="screen-reader-text">Toggle panel: Users</span>
<span class="toggle-indicator" aria-hidden="true"></span>
</button>
<h2 class="hndle ui-sortable-handle">
      <?php 
           $results = ( (object) count_users() );

           if( in_array( $profile_role, array( 'administrator' ) ) ) :
               $profile_label = "Users : " . ucfirst( strtolower( "{$profile_role}" ) ) . " - " . __( $results->total_users );
           else :
               $profile_label = 'Activity : ' . ucfirst( strtolower( $profile->data->user_login ) ) . " - " . __( $results->total_users );
           endif;
      ?> 
      <span class="events-wrap">
          <?php echo __( $profile_label, 'wp-forum' ); ?>
          <span class="clear"></span>
          <a href="<?php _e( admin_url( '/admin.php?page=settings_wp_forum' ) ); ?>" class="gear"></a>
      </span>
      
      <span class="loaders"></span>
      <input type="text" class="wp-forum__ajax_input" />
</h2>
<div class="inside users-area" id="users-manager">
<div class="main">

<div class="user-manager" id="results-users__manager">
    <ul class="user-manager__list">
        <?php

            $users = db::query( 'users', 'normal' );
            
            if( $users )
            {
                foreach( $users as $users_keys => $users_res ) :
                
                     $user_info = get_userdata( $users_res->ID );
                     $role      = $user_info->roles[0];
                     $filter_str= in_array( $role, array( 'forum_role' ) );
                     $filter_int= !in_array( $users_res->ID, array( $profile_id ) );
                     
                     // if( $filter_int AND $role != 'administrator' ) :
                     
        ?>
                     <li class="item profile-<?php _e( $users_res->ID, 'wp-forum' ); ?>">
                        
                        <?php $datas = users::data_comments( $users_res->ID ); ?>
                        
                        <div class="field label-id">
                              <a href="<?php echo site_url( '/wp-admin/user-edit.php?user_id=' . intval( $users_res->ID ) ); ?>" class="id-link"><?php echo __( $users_res->ID, 'wp-forum' ); ?></a>
                        </div>
                        
                        <div class="field label-thumb">
                              <div class="thumb-inner">
                              
                              <?php
                                $profiles_vals = users::users_profile( $users_res->ID );
        
                                if( isset( $profiles_vals ) and count( $profiles_vals ) != 0  ) 
                                {
                                    _e( "<img class='avatar avatar-96 photo' width='96' height='96' src='{$profiles_vals[0]}' alt=''>", 'wp-forum' ); 
                                } else {
                                    _e( user::gravatar( $profile->data->user_email ), 'wp-forum' ); 
                                }
                              ?>

                              </div>
                        </div>
                        
                        <div class="field label-login"><?php echo __( $users_res->user_login, 'wp-forum' ); ?></div>
                        <div class="field label-email"><?php echo __( $users_res->user_email, 'wp-forum' ); ?></div>
                        
                        <div class="field label-comments">
                            <?php 
                                   
                                   if( isset( $datas[0] ) ) 
                                   {
                                       /**
                                       echo '<pre>';
                                       var_dump( $datas );
                                       echo '</pre>';
                                       **/ 
                                       
                                       foreach( $datas[0] as $datas_keys => $datas_res ) :
                                             
                                             $comment_ID      = $datas_res->comment_ID;
                                             $post_ID_links   = get_permalink( $datas_res->comment_post_ID );
                                             $comment_content = $datas_res->comment_content;
                                             $comment_date    = $datas_res->comment_date_gmt;
                                             
                                             _e( "<div class='comments-contents comments-{$comment_ID}'><a href='{$post_ID_links}' title='Post ID - {$datas_res->comment_post_ID}' class='comments-post__id'></a> {$comment_content}</div>", 'wp-forum' );

                                       endforeach;
                                   }

                            ?>
                        </div>
                        
                        <div class="field label-action">
                            
                            <?php
                                $comments = users::counts_comments( $users_res->ID );
                            ?>
                            
                            <a href="<?php echo site_url( '/wp-admin/edit.php?post_type=post&author=' . intval( $users_res->ID ) ); ?>" class="users__comments">
                               <span><?php echo __( $comments['post_count'] ? $comments['post_count'] : $comments['comment_approved'], 'wp-forum' ); ?></span>
                               <div class="users__comments-box">
                                  Comments - <?php echo __( $comments['post_count'] ? $comments['post_count'] : $comments['comment_approved'], 'wp-forum' ); ?>
                               </div>
                            </a>
                            <a href="#replys" class="users__replys">
                               <span><?php echo __( $comments['post_total'] ? $comments['post_total'] : 0, 'wp-forum' ); ?></span>
                               <div class="users__replys-box">
                                  Replys - <?php echo __( $comments['post_total'] ? $comments['post_total'] : 0, 'wp-forum' ); ?>
                               </div> 
                            </a>
                            <a href="#likes" class="users__likes comments-hidden__none">
                               <span><?php echo __( '0', 'wp-forum' ); ?></span>
                               <div class="users__likes-box">
                                   Likes - <?php echo __( '0', 'wp-forum' ); ?>
                               </div> 
                            </a>
                            <a href="#shares" class="users__shares comments-hidden__none">
                               <span><?php echo __( '0', 'wp-forum' ); ?></span>
                               <div class="users__shares-box">
                                    Shares - <?php echo __( '0', 'wp-forum' ); ?>
                               </div>
                            </a>
                            
                        </div>
                        
                     </li>  
        <?php
                     // endif;
                     
                endforeach;
                
            }
        ?>
    </ul>
</div>

</div>
</div>

<div class="post-manager">
     <?php
         
        $sql_posts = db::posts( $profile_id );
        
        if( $sql_posts ) 
        {
            $i = 0; 
            foreach( $sql_posts as $posts_keys => $posts ) :
               
            $content_post = get_post( $posts->ID );
     ?>
     
     <?php if( !is_null( $content_post->post_content ) and isset( $content_post->post_content ) and !empty( $content_post->post_content ) ) : ?>
     
     <div class="post-contents item-<?php echo $i; ?>"><a href="<?php echo get_edit_post_link( $posts->ID ); ?>"></a><?php echo __( $content_post->post_content, 'wp-forum' ); ?></div>          
           
     <?php $i++; endif; ?>
     
     <?php endforeach; } ?>
</div>

<div class="clear"></div>

</div>
<!-- #################### tab 1 END ################### -->

<!-- #################### tab 2 ################### -->
<div id="dashboard_activity" class="postbox ">
<button type="button" class="handlediv button-link" aria-expanded="true">
<span class="screen-reader-text">Toggle panel: Activity</span>
<span class="toggle-indicator" aria-hidden="true"></span>
</button>
<h2 class="hndle ui-sortable-handle"><?php echo __( 'Profile : ' .ucfirst( strtolower( $profile->data->user_login ) ) , 'wp-forum' ); ?></h2>

<div class="inside">
<div id="activity-widget">
<div id="published-posts" class="activity-block">
    <div class="comment-status">
       
       <?php
          $comments_counts = users::accounts_comments( $profile_id );   
          
          $post_counts = $comments_counts['post_count'] ? $comments_counts['post_count'] : $comments_counts['comment_approved'];
          $post_totals = $comments_counts['post_total'] ? $comments_counts['post_total'] : 0;
       ?>
    
       <div class="comment-status__comment"><?php echo __( "COMMENT", 'wp-forum' ); ?> <span class="status-nums"><?php echo __( "({$post_counts})", 'wp-forum' ); ?></span></div>
       <div class="comment-status__reply"><?php echo __( "REPLY", 'wp-forum' ); ?> <span class="status-nums"><?php echo __( "({$post_totals})", 'wp-forum' ); ?></span></div>
       <div class="comment-status__like comments-hidden__none"><?php echo __( "LIKE", 'wp-forum' ); ?> <span class="status-nums"><?php echo __( '(0)', 'wp-forum' ); ?></span></div>
       <div class="comment-status__share comments-hidden__none"><?php echo __( "SHARE", 'wp-forum' ); ?> <span class="status-nums"><?php echo __( '(0)', 'wp-forum' ); ?></span></div>
    </div>
</div>

<?php
        $wp_list_table = _get_list_table('WP_Comments_List_Table');
        $pagenum = $wp_list_table->get_pagenum();
        
        $doaction = $wp_list_table->current_action();
        
        if ( $doaction ) {
        check_admin_referer( 'bulk-comments' );
        
        if ( 'delete_all' == $doaction && !empty( $_REQUEST['pagegen_timestamp'] ) ) {
        	$comment_status = wp_unslash( $_REQUEST['comment_status'] );
        	$delete_time = wp_unslash( $_REQUEST['pagegen_timestamp'] );
        	$comment_ids = $wpdb->get_col( $wpdb->prepare( "SELECT comment_ID FROM $wpdb->comments WHERE comment_approved = %s AND %s > comment_date_gmt", $comment_status, $delete_time ) );
        	$doaction = 'delete';
        } elseif ( isset( $_REQUEST['delete_comments'] ) ) {
        	$comment_ids = $_REQUEST['delete_comments'];
        	$doaction = ( $_REQUEST['action'] != -1 ) ? $_REQUEST['action'] : $_REQUEST['action2'];
        } elseif ( isset( $_REQUEST['ids'] ) ) {
        	$comment_ids = array_map( 'absint', explode( ',', $_REQUEST['ids'] ) );
        } elseif ( wp_get_referer() ) {
        	wp_safe_redirect( wp_get_referer() );
        	exit;
        }
        
        $approved = $unapproved = $spammed = $unspammed = $trashed = $untrashed = $deleted = 0;
        
        $redirect_to = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'spammed', 'unspammed', 'approved', 'unapproved', 'ids' ), wp_get_referer() );
        $redirect_to = add_query_arg( 'paged', $pagenum, $redirect_to );
        
        wp_defer_comment_counting( true );
        
        foreach ( $comment_ids as $comment_id ) { // Check the permissions on each
        	if ( !current_user_can( 'edit_comment', $comment_id ) )
        		continue;
        
        	switch ( $doaction ) {
        		case 'approve' :
        			wp_set_comment_status( $comment_id, 'approve' );
        			$approved++;
        			break;
        		case 'unapprove' :
        			wp_set_comment_status( $comment_id, 'hold' );
        			$unapproved++;
        			break;
        		case 'spam' :
        			wp_spam_comment( $comment_id );
        			$spammed++;
        			break;
        		case 'unspam' :
        			wp_unspam_comment( $comment_id );
        			$unspammed++;
        			break;
        		case 'trash' :
        			wp_trash_comment( $comment_id );
        			$trashed++;
        			break;
        		case 'untrash' :
        			wp_untrash_comment( $comment_id );
        			$untrashed++;
        			break;
        		case 'delete' :
        			wp_delete_comment( $comment_id );
        			$deleted++;
        			break;
        	}
        }
        
        wp_defer_comment_counting( false );
        
        if ( $approved )
        	$redirect_to = add_query_arg( 'approved', $approved, $redirect_to );
        if ( $unapproved )
        	$redirect_to = add_query_arg( 'unapproved', $unapproved, $redirect_to );
        if ( $spammed )
        	$redirect_to = add_query_arg( 'spammed', $spammed, $redirect_to );
        if ( $unspammed )
        	$redirect_to = add_query_arg( 'unspammed', $unspammed, $redirect_to );
        if ( $trashed )
        	$redirect_to = add_query_arg( 'trashed', $trashed, $redirect_to );
        if ( $untrashed )
        	$redirect_to = add_query_arg( 'untrashed', $untrashed, $redirect_to );
        if ( $deleted )
        	$redirect_to = add_query_arg( 'deleted', $deleted, $redirect_to );
        if ( $trashed || $spammed )
        	$redirect_to = add_query_arg( 'ids', join( ',', $comment_ids ), $redirect_to );
        
        wp_safe_redirect( $redirect_to );
        exit;
        } elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
         wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
         exit;
        }
?>

<div id="latest-comments" class="activity-block">
<h3><a href="<?php echo site_url( '/wp-admin/edit-comments.php', 'https' ); ?>" class="comments-headers">Comments</a></h3>
<div id="the-comment-list" data-wp-lists="list:comment">
		<div id="comment-1" class="comment even thread-even depth-1 comment-item approved">

            <div class="dashboard-profiles-wrap">
                
                <a href="<?php echo site_url( '/wp-admin/profile.php', 'https' ); ?>" class="profile-link">
                    <?php 
                       
                       $profiles_vals = users::users_profile( $profile_id );

                       if( isset( $profiles_vals ) and count( $profiles_vals ) != 0 ) 
                       {
                           _e( "<img class='avatar avatar-96 photo' width='96' height='96' src='{$profiles_vals[0]}' alt=''>", 'wp-forum' ); 
                       } else {
                           _e( user::gravatar( $profile->data->user_email ), 'wp-forum' ); 
                       }
                    ?>
                    <input type="hidden" class="profiles-input__hidden" value="<?php _e( isset( $profiles_vals ) ? $profiles_vals[0] : null, 'wp-forum' ); ?>" />
                    <input type="hidden" class="profiles-id__hidden" value="<?php _e( $profile_id, 'wp-forum' ); ?>" />
                </a>
                <a href="#" class="browse-profiles"><?php _e( "Browse", 'wp-forum' ); ?></a>
                
    			<div class="dashboard-comment-wrap has-row-actions">
        			<p class="comment-meta">
                        <?php
                           $accounts = users::accounts( $profile_id );
        
                           if( !empty( $accounts[1] ) ) : foreach( $accounts[1] as $accounts_keys => $accounts_res ) :
                               
                               $comment_ID      = $accounts_res->comment_ID;
                               $post_ID_links   = get_permalink( $accounts_res->comment_post_ID );
                               $comment_content = $accounts_res->comment_content;
                               $comment_date    = $accounts_res->comment_date_gmt;
                           
                               _e( "<span class='comments-date'><a href='{$post_ID_links}' title='Post ID - {$accounts_res->comment_post_ID}' class='comments-post__id'></a> {$comment_date}</span>", 'wp-forum' );
                               _e( "<div class='comments-contents comments-{$comment_ID}'>{$comment_content}</div>", 'wp-forum' );
                                                     
                               endforeach;
                           endif;
                        ?>
                    </p>
        
                    <p class="row-actions"></p>
                
    			</div>
                
            </div>
            
            <div class="dashboard-lefts-wrap">
            
                <div class="dashboard-lefts__inner"><?php _e( form::posts(), 'wp-forum' ); ?></div>
                
            </div>
            
            <div class="clear"></div>
            
		</div>
</div>

<!-- #################### subsubsub - start / ul > li ################### -->

<?php 
    
    $accounts_counts = users::all_accounts( 'comments' );
    $counts_1 = array();
    $counts_2 = null;

    if( is_array( $accounts_counts ) ) 
    { 
        foreach( $accounts_counts as $accounts_counts_keys => $accounts_counts_vals ) :
            $counts_1[] = ( $accounts_counts_vals ); 
        endforeach;
    }
    
    if( is_array( $counts_1 ) ) 
    {
        foreach( $counts_1 as $counts_1_keys => $counts_1_res ) :
               $counts_2 += count( $counts_1[$counts_1_keys] );
               $counts_3 = $counts_1[$counts_1_keys];
        endforeach;
        
        $approved = ( $counts_2 + 1 );
    }
        
?>

<?php $wp_list_table->views(); ?>

<!-- #################### subsubsub - end ################### -->

</div>
</div>
</div>
</div>
<!-- #################### tab 2 END ################### -->

</div>	
</div>
</div>

<?php echo html::wrap_end (); ?>