<?php

namespace Duzz\Base;

use Duzz\Base\Admin\Duzz_Admin_Menu_Items;
use Duzz\Utils\Duzz_Keys;
use Duzz\Shared\Layout\Duzz_Layout;

class Duzz_Activation {
    private $plugin_file;

    public function __construct($plugin_file) {
        $this->plugin_file = $plugin_file;

        add_action('init', array($this, 'duzz_register_nav_menus'));
        add_filter('nav_menu_css_class', array($this, 'duzz_remove_page_item_class'), 9998, 3);
        add_action('load-nav-menus.php', array($this, 'duzz_auto_nav_creation_primary'));
        $this->duzz_run_plugin_activation_hooks();
    }

    public function duzz_run_plugin_activation_hooks() {
        register_activation_hook($this->plugin_file, function() {
            $this->duzz_init(); // Call your init method
            $this->duzz_auto_nav_creation_primary(); // Call your menu setup function
            $this->duzz_save_default_connector_settings();
            $this->duzz_set_default_acf_keys(); // Call the function to set default ACF keys
            
            // Add custom rewrite rules
            $duzz_layout = new Duzz_Layout();
            $duzz_layout->duzz_register_rewrite_rules();
            
            // Flush rewrite rules after adding new rules
            flush_rewrite_rules();
        });
    }

    function duzz_init() {
        if (!get_option('duzz_has_run_install_process', false)) {
            $this->duzz_insert_user();
            $this->duzz_company_plugin_activation();
            $this->duzz_plugin_activation();
            
            update_option('duzz_has_run_install_process', true);
        }
    }

    private function duzz_set_default_acf_keys() {
        $default_keys = Duzz_Keys::$keys;
        update_option('duzz_acf_settings_acf_keys_list_field_data', $default_keys);
    }

public function duzz_save_default_connector_settings() {
    $all_settings = Duzz_Admin_Menu_Items::duzz_settings_list_data();

    foreach ($all_settings as $page_name => $sections) {
        foreach ($sections as $section_name => $section_data) {
            if (isset($section_data['data'])) {
                $settings_to_save = $section_data['data'];
                // Construct the option name to save the settings
                $option_name = $page_name . '_' . $section_name . '_field_data';
                // Save the settings data
                $update_result = update_option($option_name, $settings_to_save);
            }
        }
    }

    // Optionally, flush rewrite rules or perform other setup tasks.
    flush_rewrite_rules();
}



 public function duzz_register_nav_menus() {
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



public function duzz_auto_nav_creation_primary()
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


        $locations = get_nav_menu_locations();
    $locations['duzz-sidebar'] = $menu->term_id;
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
'user_email' => 'bot@help@duzz.io',
'display_name' => 'Owneradmin',
'nickname' => 'admin',
'first_name' => 'Admin',
'role' => 'duzz_admin'
);

$user_id = wp_insert_user( $owner_data );
}

}