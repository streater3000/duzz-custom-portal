<?php

namespace Duzz\Base\Admin;

use Duzz\Base\Admin\Duzz_Admin_Menu_Items;
use Duzz\Base\Admin\Factory\Duzz_Select2_Enqueue;

class Duzz_Admin {
    private static $instance = null;
    private $menu_slug;

    public static function duzz_getInstance($plugin_file, $menu_slug) { 
        if (self::$instance === null) {
            self::$instance = new self($menu_slug); 
        }
        return self::$instance;
    }


    private function __construct($menu_slug) { // Modify the constructor to accept the menu slug
        $this->menu_slug = $menu_slug;

        if (!class_exists('WP_REST_Request')) {
            require_once ABSPATH . WPINC . '/rest-api/class-wp-rest-request.php';
        }

        add_action('admin_menu', array($this, 'duzz_ActiveAdminMenu'));
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


    public function duzz_ActiveAdminMenu() {
        // Use $this->menu_slug instead of the hardcoded slug:
        add_menu_page(
            "DuzzClientPortal",
            "Duzz Portal",
            "activate_plugins",
            $this->menu_slug, // Use the property here
                    array(Duzz_Admin_Menu_Items::class, "duzz_forms_admin_settings_connector_callback"),
            "dashicons-star-filled",
            2
        );

add_submenu_page(
    $this->menu_slug,
    'Stripe Keys Settings',
    'Stripe Keys',
    "activate_plugins",
    'duzz_forms_stripe_keys_connector',
    array(Duzz_Admin_Menu_Items::class, "duzz_forms_stripe_keys_connector_callback")
);


   add_submenu_page(
    $this->menu_slug, // Parent menu slug
    'ACF Keys Connector',
    'ACF Keys',
    "activate_plugins",
    'duzz_forms_acf_values_connector', // Unique submenu slug
    array(Duzz_Admin_Menu_Items::class, "duzz_forms_acf_values_connector_callback") // Pass the callback properly
);

add_submenu_page(
    $this->menu_slug, // Parent menu slug
    "WPForms Client Connector",
    "WP Forms Client",
    "activate_plugins",
    "duzz_forms_client_connector", // Unique submenu slug
    array(Duzz_Admin_Menu_Items::class, "duzz_forms_client_connector_callback") // Pass the callback properly
);

add_submenu_page(
    $this->menu_slug, // Parent menu slug
    'WPForms Admin Connector',
    'WP Forms Admin',
    "activate_plugins",
    'duzz_forms_admin_connector', // Unique submenu slug
    array(Duzz_Admin_Menu_Items::class, "duzz_forms_admin_connector_callback") // Pass the callback properly
);

}
}

