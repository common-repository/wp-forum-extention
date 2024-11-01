<?php if( !class_exists( 'wp_forum_page' ) ) 
{
    
    class wp_forum_page {
        
        public static $name        = "WP Forum";
        public static $icon        = "wp-forum/images/seo_web-10-16.png";
        public static $plugin_slug = 'wp_forum';
        public static $folder      = 'wp-forum';
        public static $shortcode   = 'wp_forum';

        protected $values          = array();
        private static $instance; 

        public static function getInstance () { 
              if( !self::$instance ) { self::$instance = new self(); }
              return self::$instance;
        } 

        public function __get( $key ){ return $this->values[$key]; }
        public function __set( $key, $value ){ $this->values[$key] = $value; }
        public function __isset( $key ){ $this->values[$key]; }
        public function __unset( $key ){ $this->values[$key]; }
        
        public static function forum_role_capabilities () 
        {
             
             return array( 
                           'activate_plugins'       => false,
                           'delete_others_pages'    => true,
                           'delete_others_posts'    => true,
                           'delete_pages'           => true,
                           'delete_posts'           => true,
                           'delete_private_pages'   => true,
                           'delete_private_posts'   => true,
                           'delete_published_pages' => true,
                           'delete_published_posts' => true,
                           'edit_dashboard'         => true,
                           'edit_others_pages'      => true,
                           'edit_others_posts'      => true,
                           'edit_pages'             => true,
                           'edit_posts'             => true,
                           'edit_private_pages'     => true,
                           'edit_private_posts'     => true,
                           'edit_published_pages'   => true,
                           'edit_published_posts'   => true,
                           'edit_theme_options'     => true,
                           'export'                 => true,
                           'import'                 => true,
                           'list_users'             => true,
                           'manage_categories'      => true,
                           'manage_links'           => true,
                           'manage_options'         => true,
                           'moderate_comments'      => true,
                           'promote_users'          => true,
                           'publish_pages'          => true,
                           'publish_posts'          => true,
                           'read_private_pages'     => true,
                           'read_private_posts'     => true,
                           'read'                   => true,
                           'remove_users'           => true,
                           'switch_themes'          => true,
                           'upload_files'           => true 
                           ); 
              
        }
        
        function __construct () 
        {
              global $wpdb;
              
              add::action_page( array($this, 'admin_page') );
              
              /**
                * Backend Style
              **/
                 
              add::style(true, self::$plugin_slug.'-admin-style', self::$folder.'/css/admin.css' );
    
              
              /**
               * Front Style
              **/
              
              add::style(false, self::$plugin_slug.'-front-style', self::$folder.'/css/front.css' );
                
              /**
                * Backend Script 
              **/
    
              add::wp_script('jquery');
              add::wp_script('jquery-ui-sortable');
              add::wp_script('jquery-ui-draggable');
              add::wp_script('jquery-ui-droppable');
              
              add::wp_script('jquery-ui-core');
              add::wp_script('jquery-ui-dialog');
              add::wp_script('jquery-ui-slider');
              
              add::script(true, self::$plugin_slug.'admin-script', self::$folder.'/js/admin.js' );
              add::script(true, self::$plugin_slug.'sort-script', self::$folder.'/js/sort.js' );
              
              add::script(true, self::$plugin_slug.'ajax_handler', self::$folder.'/js/ajax.js' );
              add::localize_script( true, self::$plugin_slug.'ajax_handler', 'ajax_script', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
              
              /**
                * Frontend Script 
              **/
              
              add::script(false, self::$plugin_slug.'front-script', self::$folder.'/js/front.js' );
              
              // wp_media

              add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
              
              // actions option
              
              add::action_loaded ( array( $this, 'update_db_check' ) );
              
              // actions shortcode callback
              
              add::shortcode ( self::$shortcode, array( $this, self::$shortcode.'_shortcode_function' ) );
              
              // add role
              
              $defaults = array( 'read' => true, 'manage_options' => true, 'wp_forum' => true, 'edit_posts' => true, 'delete_posts' => false );
              add_role( 'forum_role', __( 'Forum Subscriber' ), $defaults );
              
              // ajax actions
              
              add::action_ajax( array( $this, 'ajaxs_clear' ) );  
              add::action_ajax( array( $this, 'ajaxs_search' ) ); 
              add::action_ajax( array( $this, 'ajaxs_settings_add' ) );           
              add::action_ajax( array( $this, 'ajaxs_profiles_edit' ) );   
              add::action_ajax( array( $this, 'ajaxs_posts_draft' ) );   
              
              // wp custom actions
              
              add_action( 'admin_init', array( $this, 'get_role' ) );
        } 
        
        public static function admin_scripts ()
        {
              add::wp_media( true );
        }
        
        public static function get_role_caps () 
        {
                global $wp_roles;

                if ( ! isset( $wp_roles ) ) 
                {
                     $wp_roles = new WP_Roles();
                }
                if( is_array( $this->caps ) )
                {
                    $this->roles = array_filter( array_keys( $this->caps ), array( $wp_roles, 'is_role' ) );
                }
                $this->allcaps = self::forum_role_capabilities();
                foreach( ( array ) $this->roles as $role ) 
                {
                        $role = $wp_roles->get_role( $role );
                        $this->allcaps = array_merge( $this->allcaps, $role->capabilities );
                }
                $this->allcaps = array_merge( $this->allcaps, $this->caps );
        }
        
        function get_role () 
        {       
                $role = get_role( 'subscriber' );
                
                $caps = self::forum_role_capabilities();
                
                foreach( $caps as $caps_key => $caps_result ) : $role->add_cap( $caps_key ); endforeach;
        }
        
        public function admin_page () 
        {
            $roles = wp_get_current_user();
            
            $roles_id = $roles->data->ID; 
            $wp_roles = $roles->roles[0];

            if ( in_array( $wp_roles, array( 'subscriber', 'editor' ) ) )  
            {
                 
                 $menu[] = array( self::$name, self::$name, 1, self::$plugin_slug, array( $this,  self::$plugin_slug.'_function'), self::$icon );
                   
            } else {
                
                 $menu[] = array( self::$name, self::$name, 1, self::$plugin_slug, array( $this,  self::$plugin_slug.'_function'), self::$icon );
                 // $menu[] = array( 'Add New', 'Add New', 1, self::$plugin_slug, 'add_new_option_'.self::$plugin_slug, array( $this, 'add_new_option_'.self::$plugin_slug.'_function' ) );
                 $menu[] = array( 'Settings', 'Settings', 1, self::$plugin_slug, 'settings_'.self::$plugin_slug, array( $this, 'settings_'.self::$plugin_slug.'_function' ) );
                 // $menu[] = array( 'Help?', 'Help?', 1, self::$plugin_slug, 'help_'.self::$plugin_slug, array( $this, 'help_'.self::$plugin_slug.'_function' ) );

            }
            
            if( is_array( $menu ) ) 
            {
                add::load_menu_page( $menu );
            }
        }
        
        public function update_db_check () {
            global $db_version;
            
            if ( get_site_option( 'db_version' ) != $db_version ) {
                self::install();
            }
        }
        
        // dbDelta
        
        public static function install () {
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();
            $table_name = $wpdb->prefix . ( 'wpforum_profile' );

            $sql = "CREATE TABLE {$table_name} (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              name tinytext NOT NULL,
              text text NOT NULL,
              UNIQUE KEY id (id)
            ) $charset_collate;";
            
 
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql ); 

        }
        
        // view 
        
        public function wp_forum_function() 
        {
            load::view( 'manage' );
        }
        
        public function add_new_option_wp_forum_function() 
        {
            load::view( 'add' );
        }
        
        public function settings_wp_forum_function() 
        {
            load::view( 'settings' );
        }
        
        public function help_wp_forum_function() 
        {
            load::view( 'help' );
        }
        
        // view/shortcode
        
        public function wp_forum_shortcode_function($atts) 
        {
            
            $attrs = ( object ) shortcode_atts( array ( 'forum'  => true, 'social' => false ), $atts );
            
            if( $attrs->forum == true ) : 
                load::view( 'shortcode/wp-forum-shortcode' ); 
            endif;
        }
        
        // ajax actions
        
        public function ajaxs_clear () 
        {
            action::clear();
            die();
        }
        
        public function ajaxs_search () 
        {
            $posts = input::post_is_object();
            action::search( $posts );
            die();
        }
        
        public function ajaxs_settings_add () 
        {
            $posts = input::post_is_object();
            action::insert( $posts );
            die();
        }
        
        public function ajaxs_profiles_edit () 
        {
            $posts = input::post_is_object();
            action::insert_profile( $posts );
            die();
        }
        
        public function ajaxs_posts_draft()
        {
            $posts = input::post_is_object();
            action::insert_posts( $posts );
            die();
        }
    
    }

}  

new wp_forum_page( true );
?>