<?php

namespace Duzz\Base\Admin;

use Duzz\Base\Admin\Factory\Duzz_Forms_Connector;
use Duzz\Utils\Duzz_Keys;

class Duzz_Admin_Menu_Items {
    public static $field_data;
    public static $field_filtered_data;
    public static $removedKeys;
     private static $connectors = [];

public static function duzz_create_forms_connectors() {
    global $duzz_forms_acf_values_connector;

        Duzz_Keys::duzz_init();
        self::$field_data = Duzz_Keys::duzz_get_keys();

        self::$field_data = apply_filters('modify_duzz_field_data', self::$field_data);

        self::$field_filtered_data = Duzz_Keys::duzz_get_filtered_keys();

        self::$removedKeys = Duzz_Keys::duzz_get_keys_removed([
            'last_updated',
            'archived',
            'approved_status',
            'staff_notes',
            'customer_status',
            'approved_status',
            'project_status',
            'customer_address'
        ]);

    $duzz_forms_acf_values_connector = new Duzz_Forms_Connector('duzz_acf_settings', true);
        $duzz_forms_acf_values_connector->duzz_add_section( self::$field_data, 'acf_keys_list');

    $duzz_forms_acf_values_connector->duzz_init();
    }

public static function duzz_forms_acf_values_connector_callback() {
    // Check user capabilities
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    global $duzz_forms_acf_values_connector;
    $option_group = $duzz_forms_acf_values_connector->duzz_get_option_group();
    $page_slug = $duzz_forms_acf_values_connector->duzz_get_page_slug();

    $duzz_forms_acf_values_connector->duzz_output_form( $option_group, $page_slug );

}

    public static function initialize_connectors() {
        $settings = self::duzz_settings_list_data();

        foreach ($settings as $page_slug => $page_settings) {
            self::$connectors[$page_slug] = new Duzz_Forms_Connector($page_slug);
            self::add_sections_dynamically(self::$connectors[$page_slug], $page_settings);
            self::$connectors[$page_slug]->duzz_init();
        }
    }

    public static function get_connector($page_slug) {
        return self::$connectors[$page_slug] ?? null;
    }

    public static function generic_connector_callback($page_slug) {
        if (!current_user_can('activate_plugins')) {
            return;
        }
        $connector = self::get_connector($page_slug);
        if ($connector) {
            $option_group = $connector->duzz_get_option_group();
            $connector->duzz_output_form($option_group, $page_slug);
        }
    }

    public static function add_sections_dynamically($connector, $settings) {
        foreach ($settings as $section_key => $section_data) {
            $data = $section_data['data'];
            $form_type = isset($section_data['form_type']) ? $section_data['form_type'] : 'table'; // Default to 'text' if not specified
            $extra = isset($section_data['extra']) ? $section_data['extra'] : null; // Handle optional fourth parameter

            // Add the section to the connector
            $connector->duzz_add_section($data, $section_key, $form_type, $extra);
        }
    }

    public static function duzz_settings_list_data() {
        return array(
        'stripe_settings' => array(
            'stripe_keys_data' => array(
                'data' => array(
                    'API_secret_key_live' => '',
                    'API_publishable_key_live' => '',
                ),
                'form_type' => 'text' // specify the form type
            ),
            'stripe_test_toggle' => array(
                'data' => array(
                    'Stripe_Test_API' => '',
                ),
                'form_type' => 'toggle' // specify the form type
            ),
            'stripe_test_keys' => array(
                'data' => array(
                    'API_secret_key_test' => '',
                    'API_publishable_key_test' => '',
                ),
                'form_type' => 'text' // specify the form type
            ),
            // Add other Stripe settings as needed...
        ),
         'duzz_settings' => array(
            'API_keys' => array(
                'data' => array(
                    'duzz_license_key' => ' ',
                ),
                'form_type' => 'text' // specify the form type
            ),
            'list_projects' => array(
                'data' => array(
                    'selected_columns_data_title' => array('customer_name'),
                    'selected_columns' => array('customer_email', 'website'),
                ),
                'form_type' => 'select2', // specify the form type
                'extra' => self::$removedKeys
            ),
            'email_settings' => array(
                'data' => array(
                    'admin_email' => 'admin@stark.io',
                    'admin_name' => 'Tony Stark',
                    'company_name' => 'Stark Industries',
                ),
                'form_type' => 'text' // specify the form type
            ),
            'project_page' => array(
                'data' => array(
                    'main_data' => array('website'),
                    'header_data' => array('customer_phone', 'customer_email', 'approved_status', 'last_updated'),
                    'info_tab_fields' => array('project_status', 'approved_status', 'staff_notes', 'website'),
                    'updates_tab_fields' => array('customer_email', 'customer_phone', 'customer_address', 'customer_name'),
                ),
                'form_type' => 'select2',
                'extra' => self::$field_filtered_data
            ),
            'welcome_message' => array(
                'data' => array(
                    'message' => 'Welcome! How can we help you?',
                ),
                'form_type' => 'textarea' // specify the form type
            ),
            'toggle_redirect' => array(
                'data' => array(
                    'redirect_to_workspace_on_login' => '',
                ),
                'form_type' => 'toggle' // specify the form type
            ),
            'acf_group' => array(
                'data' => array(
                    'ACF_Group_ID_1' => 9900,
                    'ACF_Group_ID_2' => 9961,
                    'ACF_Group_ID_3' => 9965,
                ),
                'form_type' => 'text' // specify the form type
            ),
            'remove_keys' => array(
                'data' => array(
                    'ACF_Key_1' => 'field_8e40b9c536d68e',
                    'ACF_Key_2' => 'field_8a80c7d537a68d',
                    'ACF_Key_3' => 'field_8f50b9f539a60c',
                ),
                'form_type' => 'text' // specify the form type
            ),
            // Add other sections as needed...
        ),


    'wp_forms_client' => array(
            'client_field_numbers' => array(
                'data' => array(
                    'customer_ip' => 45,
                    'customer_address' => 41,
                    'customer_name' => 1,
                    'customer_email' => 3,
                    'customer_phone' => 9,
                    'website' => 49,
                    'staff_id' => 46,
                    'team_id' => 47,
                    'company_id' => 48,
                    'customer_message' => 54,
                ),
                // form_type is omitted, defaulting to 'text'
            ),
            'client_form_id' => array(
                'data' => array(
                    'form_id' => 9937,
                ),
                // form_type is omitted, defaulting to 'text'
            ),
            // Add other client settings as needed
        ),

        'wp_forms_admin' => array(
            'admin_field_numbers' => array(
                'data' => array(
                    'customer_address' => 11,
                    'customer_name' => 19,
                    'customer_email' => 20,
                    'customer_phone' => 21,
                    'website' => 26,
                    'staff_id' => 2,
                    'team_id' => 24,
                    'company_id' => 25,
                    'company_name' => 27,
                ),
                // form_type is omitted, defaulting to 'text'
            ),
            'admin_form_id' => array(
                'data' => array(
                    'form_id' => 9933,
                ),
                // form_type is omitted, defaulting to 'text'
            ),
            // Add other admin settings as needed
        ),

            // Add other pages and their settings as needed
        );
        if ($key === null) {
            return $settings;
        }

        return isset($settings[$key]) ? $settings[$key] : array();
    }
}
