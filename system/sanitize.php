<?php if( !class_exists('sanitize')){
    
     class sanitize{
         
         public static function ent2ncr($text=""){
             return ent2ncr($text);
         }
         
         // text nodes
         
         public static function esc_html($text=""){
             return esc_html($text);
         }
         
         public static function esc_textarea($text=""){
             return esc_textarea($text);
         }
         
         public static function sanitize_text_field($str=""){
             return sanitize_text_field($str);
         }
         
         // attribute node
         
         public static function esc_attr($str=""){
             return esc_attr($str);
         }
         
         // javascript
         
         public static function esc_js($text=""){
             return esc_js($text);
         }
         
         // slugs
         
         public static function sanitize_title($title=""){
             return sanitize_title($title);
         }
         
         public static function sanitize_user($username,$strict=false){
             return sanitize_user($username,$strict);
         }
         
     }
    
}
?>