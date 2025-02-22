<?php

$comments_args = array (
        'label_submit'        => 'Send',
        'title_reply'         => 'Write a Reply or Comment',
        'comment_notes_after' => 'xx',
        'comment_field'       => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><br /><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
);

$args = array(
      'id_form'           => 'commentform',
      'class_form'        => 'comment-form',
      'id_submit'         => 'submit',
      'class_submit'      => 'submit',
      'name_submit'       => 'submit',
      'title_reply'       => __( 'Leave a Reply' ),
      'title_reply_to'    => __( 'Leave a Reply to %s' ),
      'cancel_reply_link' => __( 'Cancel Reply' ),
      'label_submit'      => __( 'Post Comment' ),
      'format'            => 'xhtml',
    
      'comment_field' =>  '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) .
            '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
            '</textarea></p>',
    
      'must_log_in' => '<p class="must-log-in">' .
            sprintf(
              __( 'You must be <a href="%s">logged in</a> to post a comment.' ),
              wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
            ) . '</p>',
    
      'logged_in_as' => '<p class="logged-in-as">' .
            sprintf(
            __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ),
              admin_url( 'profile.php' ),
              $user_identity,
              wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
            ) . '</p>',
    
      'comment_notes_before' => '<p class="comment-notes">' .
            __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) .
            '</p>',
    
      'comment_notes_after' => '<p class="form-allowed-tags">' .
            sprintf(
              __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ),
              ' <code>' . allowed_tags() . '</code>'
            ) . '</p>',
    
      'fields' => apply_filters( 'comment_form_default_fields', $fields ),
);

$querys = new WP_Query( array( 'post_type' => 'post' ) );

if ( $querys->have_posts() ) 
{
	while ( $querys->have_posts() ) 
    {
		$querys->the_post();

        comment_form( $args, get_the_ID() );
	}
}


?>