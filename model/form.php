<?php if( !class_exists( 'form' ) ){
    
    class form {
          
          public function __construct() 
          {
               parent::__construct();
          }
          
          /**
           *  https://codex.wordpress.org/Plugin_API/Action_Reference/wp_insert_post
           *  https://developer.wordpress.org/reference/functions/wp_insert_post/
          **/
          
          public static function posts( $posts=array() ) 
          {
               $html  = null;
               
               $html .= '<div class="posts-input__title">';
               $html .= '<input type="text" class="posts-input__text-field field-posts-draft" value="" name="" />';
               $html .= '</div>';
               
               $html .= '<div class="posts-input__desc">';
               $html .= '<textarea class="posts-input__desc-field field-posts-draft">';
               $html .= '</textarea>';
               $html .= '</div>';
               
               $html .= '<div class="posts-input__submit">';
               $html .= '<input id="save-post_field" class="button button-primary" type="submit" value="Save Draft" name="" />';
               $html .= '<span class="loaders-posts-draft"></span>';
               $html .= '</div>';
               
               return $html;
          }
             
    }
}
?>