<?php

namespace Duzz\Base;

use Duzz\Utils\Duzz_Keys;

class Duzz_Activation{
    private $plugin_file;

    public function __construct($plugin_file) {
        $this->plugin_file = $plugin_file;

        add_action( 'init', array( $this, 'register_nav_menus' ) );

        add_filter('nav_menu_css_class', array($this, 'duzz_remove_page_item_class'), 9998, 3);
        add_shortcode('menu', 'print_menu_shortcode');
        add_action( 'load-nav-menus.php', array( $this, 'auto_nav_creation_primary' ) );
        $this->run_plugin_activation_hooks();
    }

    public function run_plugin_activation_hooks() {
        register_activation_hook( $this->plugin_file, array( $this, 'duzz_init' ) );
    }

    function duzz_init() {
        if ( ! get_option( 'has_run_install_process', false ) ) {
            $this->duzz_insert_user();
            $this->duzz_company_plugin_activation();
            $this->duzz_plugin_activation();
            $this->create_acf_field_group_and_field();
            update_option( 'has_run_install_process', true );
        }
    }

    public function create_acf_field_group_and_field() {
        if(post_type_exists('acf-field-group') && post_type_exists('acf-field')) {
            $group_id = wp_insert_post([
                'post_type' => 'acf-field-group',
                'post_title' => 'Duzz Fields',
                'post_status' => 'publish',
            ]);

            $keys = Duzz_Keys::$keys;
            
            foreach($keys as $name => $key) {
                $field_id = wp_insert_post([
                    'post_type' => 'acf-field',
                    'post_title' => ucwords(str_replace('_', ' ', $name)), // Convert the name to a label
                    'post_name' => $key,
                    'post_excerpt' => $name,
                    'post_status' => 'publish',
                    'post_parent' => $group_id,
                ]);

                update_post_meta($field_id, 'field_type', 'text'); // Change this to match your needs
                update_post_meta($field_id, 'field_key', $key);
                update_post_meta($field_id, 'field_name', $name);
            }
        }
    }


 public function register_nav_menus() {
        register_nav_menus( array(
            'duzz-sidebar' => 'Duzz Sidebar'
        ) );
    }


/**
 ** Add Default Company on Plugin Activation.
 **/


function duzz_company_plugin_activation() {
  
  if ( ! current_user_can( 'activate_plugins' ) ) return;
  
  global $wpdb;
  
  if ( null === $wpdb->get_row( "SELECT post_name FROM {$wpdb->prefix}posts WHERE post_name = 'new-page-slug'", 'ARRAY_A' ) ) {
     
    $current_user = wp_get_current_user();

    $company_name = 'Company';


    // create post object
    $args = array(
            'post_title'   => $company_name,
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'company',
                'import_id'  => 9909,
            'meta_input'   => array(
              'staff_id' => 262,
              'archived' => 0,
            ),
            );
    
    // insert the post into the database

$company_id = wp_insert_post( $args );

  $dep_args = array(
            'post_title'   => $company_name . ' Team',
            'post_content' => '',
            'post_status'  => 'publish',
            'post_type'    => 'team',
                'import_id'  => 9908,
            'meta_input'   => array(
              'company_id' => $company_id,
                    'archived' => 0,
            ),
            );

 $team_id = wp_insert_post( $dep_args );
  
}
}


function duzz_plugin_activation() {
    if (!current_user_can('activate_plugins')) return;

    global $wpdb;

    $pages = array(
        'workspace' => 9923,
        'project' => 9924,
        'your project' => 9925,
        'messages' => 9926,
        'start project' => 9922,
        'resend project' => 9917,
        'add project' => 9921,
        'archive'   => 9920,
    );

    foreach ($pages as $page_title => $page_id) {

        $args = array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'name' => sanitize_title($page_title),
            'posts_per_page' => 1,
        );

        $page_query = new \WP_Query($args);
        
        // If the page doesn't exist
        if (!$page_query->have_posts()) {
            $page = array(
                'post_type'   => 'page',
                'post_title'  => $page_title,
                'post_name'   => $page_title,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_parent' => '',
                'import_id'  => $page_id,
            );

            // Insert the post into the database
            $new_page_id = wp_insert_post($page);
        }

        wp_reset_postdata(); // Reset the global post data to avoid conflicts later on
    }
}



public function auto_nav_creation_primary()
{
    $name = 'Sidebar Menu';
    $menu_exists = wp_get_nav_menu_object($name);
    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($name);
        $menu = get_term_by('name', $name, 'nav_menu');

        $firstpage = get_post(9921);
        $first_menu_item_id = wp_update_nav_menu_item($menu->term_id, 0, array(
            'menu-item-title' => __('add project'),
            'menu-item-object-id' => $firstpage->ID,
            'menu-item-object' => 'page',
            'menu-item-classes' => 'add-project-menu',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish',
        ));
        update_post_meta($first_menu_item_id, '_menu_item_visibility', 'logged_in');

        $secondpage = get_post(9923);
        $second_menu_item_id = wp_update_nav_menu_item($menu->term_id, 0, array(
            'menu-item-title' => __('workspace'),
            'menu-item-object-id' => $secondpage->ID,
            'menu-item-object' => 'page',
            'menu-item-classes' => 'staff-menu-item',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish',
        ));
        update_post_meta($second_menu_item_id, '_menu_item_visibility', 'logged_in');

        $thirdpage = get_post(9926);
        $third_menu_item_id = wp_update_nav_menu_item($menu->term_id, 0, array(
            'menu-item-title' => __('messages'),
            'menu-item-object-id' => $thirdpage->ID,
            'menu-item-object' => 'page',
            'menu-item-classes' => 'staff-menu-item',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'
        ));
        update_post_meta($third_menu_item_id, '_menu_item_visibility', 'logged_in');


        $fourthpage = get_post(9920);
        $fourth_menu_item_id = wp_update_nav_menu_item($menu->term_id, 0, array(
            'menu-item-title' => __('archive'),
            'menu-item-object-id' => $fourthpage->ID,
            'menu-item-object' => 'page',
            'menu-item-classes' => 'staff-menu-item',
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'
        ));
        update_post_meta($fourth_menu_item_id, '_menu_item_visibility', 'logged_in');

        $logout_link = $this->duzz_logout_link('');
        $logout_menu_item_id = wp_update_nav_menu_item($menu->term_id, 0, array(
            'menu-item-title' => __('logout'),
            'menu-item-url' => $logout_link,
            'menu-item-classes' => 'logout-menu-item staff-menu-item',
            'menu-item-status' => 'publish',
        ));
        update_post_meta($logout_menu_item_id, '_menu_item_visibility', 'logged_in');


        $locations = get_theme_mod('nav_menu_locations');
        $locations['duzz-sidebar'] = $menu->term_id;
        $locations['footer-menu-location'] = $footer_menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}

public function duzz_logout_link($link){
    $args = array('a' => 'logout');
    $logout_url = add_query_arg($args, wp_logout_url(wp_login_url()));
    return apply_filters('duzz_logout_link', $logout_url);
}



function duzz_remove_page_item_class($classes, $item, $args) {
    if($args->theme_location == 'duzz-sidebar') { // If the location is 'sidebar'
        $classes = array_filter($classes, function($class) {
            return (substr($class, 0, 10) !== 'page-item-'); // Remove classes that start with 'page-item-'
        });
    }
    return $classes;
}

/*Create Bot*/


function duzz_insert_user() {

global $wpdb;

$wpdb->insert( $wpdb->users, array( 'ID' => 176, 'user_login' => 'bot') );
$wpdb->insert( $wpdb->users, array( 'ID' => 262, 'user_login' => 'owner') );


$bot_data = array(
'ID' => 176,
'user_pass' => wp_generate_password(),
'user_login' => 'bot',
'user_nicename' => 'Bot',
'user_url' => '',
'user_email' => 'bot@duzz.io',
'display_name' => 'Bot',
'nickname' => 'bot',
'first_name' => 'Bot',
'role' => 'duzz_bot'
);

$user_id = wp_insert_user( $bot_data );

$owner_data = array(
'ID' => 262,
'user_pass' => wp_generate_password(),
'user_login' => 'adminowner',
'user_nicename' => 'Owner',
'user_url' => '',
'user_email' => 'bot@help@duzz.io.io',
'display_name' => 'Owneradmin',
'nickname' => 'admin',
'first_name' => 'Admin',
'role' => 'duzz_admin'
);

$user_id = wp_insert_user( $owner_data );
}

}