<?php

/**
 * @author charly
 * @copyright 2016
 */
 
global $wp_roles;

echo html::wrap_start( 'wp-forum__wrap' ); 

?>

<h1><?php echo __( 'WP Forum : Settings', 'wp-forum' ); ?></h1>
<p><?php echo __( 'Administrator Roles Activition ( enabled ) to view this wp forum dashboard.', 'wp-forum' ); ?></p>

<?php
  $users = db::query( 'users', 'normal' );
  if( $users ) 
  {         
      foreach ( $users as $users_keys => $users_res ) :
          $user_info = get_userdata( $users_res->ID );
          $roles     = $user_info->roles[0];
      endforeach;
  }
?>

<div class="wp-forums__events">
    <a href="#" class="wp-forums__events-actions added"><?php _e( 'All', 'wp-forum' ); ?></a> 
    <a href="#" class="wp-forums__events-actions removed"><?php _e( 'Uncheck', 'wp-forum' ); ?></a>
</div>

<div class="wp-forums__roles">
    
    <?php
         $options = get_option( 'wpforum_settings_options' ) ? get_option( 'wpforum_settings_options' ) : null;
         $options_vals = unserialize( $options ) ? unserialize( $options ) : array();
    ?>
    
    <ul  class="wp-forums__roles-loop">
        <?php foreach( $wp_roles->roles as $roles_keys => $roles_vals ) : ?>   
           
            <?php
                  if( in_array( $roles_keys, $options_vals ) ) {
                      $set_roles = $roles_keys;
                  } 
            ?>
            
            <li class="wp-forums__roles-loop"><input type="checkbox" name="roles-types" value="<?php echo __( $roles_keys, 'wp-forum' ); ?>" class="checkbox-roles" <?php echo $roles_keys == $set_roles ? 'checked="checked"' : null; ?> /><?php echo __( ucfirst( strtolower( $roles_keys ) ), 'wp-forum' ); ?></li>    
        <?php endforeach; ?>
    </ul>
    
</div>

<div class="wp-forums__submits">
    <input id="publish" class="button button-primary button-large submit-checkbox" type="submit" value="Publish" name="publish" name="roles-submits" />
    <span class="loaders settings"></span>
</div>

<?php echo html::wrap_end (); ?>