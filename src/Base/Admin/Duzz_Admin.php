<?php

namespace Duzz\Base\Admin;

use Duzz\Base\Admin\Duzz_Admin_Menu_Items;
use Duzz\Base\Admin\Factory\Duzz_Select2_Enqueue;

class Duzz_Admin {
    private static $instance = null;

    public static function duzz_getInstance($plugin_file, $menu_slug) { 
        if (self::$instance === null) {
            self::$instance = new self($menu_slug); 
        }
        return self::$instance;
    }

    public function __construct($menu_slug) {

        if (!class_exists('WP_REST_Request')) {
            require_once ABSPATH . WPINC . '/rest-api/class-wp-rest-request.php';
        }


        add_action('admin_menu', array($this, 'duzz_init_admin_menu'));
        add_action('init', array($this, 'duzz_load_select2_assets'));
        add_action('admin_bar_menu', array($this, 'duzz_custom_admin_bar_link'), 999);
         add_action('admin_notices', array($this, 'duzz_settings_saved_notice'));

         add_filter('query_vars', array($this, 'duzz_add_settings_updated_query_var'));

    }


    public function duzz_load_select2_assets() {
        new Duzz_Select2_Enqueue();
    }

    public function duzz_add_settings_updated_query_var($vars) {
        $vars[] = 'settings-updated';
        return $vars;
    }

public function duzz_settings_saved_notice() {
    $settings_updated = get_query_var('settings-updated', false);
    if ($settings_updated == 'true') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php esc_html_e('Settings saved.', 'duzz'); ?></p>
        </div>
        <?php
    }
}


public function duzz_custom_admin_bar_link($wp_admin_bar) {
    $args = array(
        'id' => 'custom_link', // id of the existing child node (New > Post)
        'title' => 'Duzz Workspace', // alter the title of node
        'href' => home_url('/workspace/'), // set the hyperlink
        'meta' => array('target' => '_blank') // open in new tab
    );
    $wp_admin_bar->add_node($args);
}


    public function duzz_init_admin_menu() {
        // Main menu page
                add_menu_page(
            'DuzzClientPortal',
            'Duzz Portal',
            'activate_plugins',
            DUZZ_MENU_SLUG, // Use the constant for the menu slug
            function() { Duzz_Admin_Menu_Items::generic_connector_callback('duzz_settings'); },
            'dashicons-star-filled',
            2
        );


        // Hardcoded submenu page
        add_submenu_page(
            DUZZ_MENU_SLUG,
            'ACF Keys Connector',
            'ACF Keys',
            'activate_plugins',
            'duzz_forms_acf_values_connector',
            array(Duzz_Admin_Menu_Items::class, 'duzz_forms_acf_values_connector_callback')
        );




            // Dynamically add submenu pages
            $page_slugs = array_keys(Duzz_Admin_Menu_Items::duzz_settings_list_data());
            foreach ($page_slugs as $slug) {
                if ($slug !== 'duzz_settings') {
                    $title = $this->format_title($slug);
                    add_submenu_page(
                        DUZZ_MENU_SLUG,
                        $title,
                        $title,
                        'activate_plugins',
                        $slug,
                        function() use ($slug) { Duzz_Admin_Menu_Items::generic_connector_callback($slug); }
                    );
                }
            }
                }     

            // Custom function to format the title
            function format_title($slug) {
                $words = explode('_', $slug);
                $formatted_words = array_map(function($word) {
                    // Specific case for 'wp'
                    if (strtolower($word) === 'wp') {
                        return 'WP';
                    } else {
                        return ucfirst($word);
                    }
                }, $words);         

                return implode(' ', $formatted_words);
            }

}