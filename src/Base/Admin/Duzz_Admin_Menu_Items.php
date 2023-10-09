<?php

namespace Duzz\Base\Admin;

use Duzz\Base\Admin\Factory\Duzz_Forms_Connector;
use Duzz\Utils\Duzz_Keys;

class Duzz_Admin_Menu_Items {

    public static $field_data;
    public static $field_filtered_data;


public static function duzz_create_forms_connectors() {
    global $duzz_forms_admin_connector;
    global $duzz_forms_client_connector;
    global $duzz_forms_acf_values_connector;
    global $duzz_forms_settings_fields_connector;
    global $duzz_forms_stripe_keys_connector;
    global $duzz_init_processes;

        Duzz_Keys::duzz_init();
        self::$field_data = Duzz_Keys::duzz_get_keys();

        self::$field_data = apply_filters('modify_duzz_field_data', self::$field_data);

        self::$field_filtered_data = Duzz_Keys::duzz_get_filtered_keys();

        $removedKeys = Duzz_Keys::duzz_get_keys_removed([
            'last_updated',
            'archived',
            'approved_status',
            'staff_notes',
            'customer_status',
            'approved_status',
            'project_status',
            'customer_address'
        ]);



    $duzz_forms_admin_connector = new \Duzz\Base\Admin\Factory\Duzz_Forms_Connector('duzz_admin');
    $duzz_forms_admin_connector->duzz_add_section(self::duzz_settings_list_data('admin_form_id'), 'form_id');
    $duzz_forms_admin_connector->duzz_add_section(self::duzz_settings_list_data('admin_field_numbers'), 'field_numbers');

    $duzz_forms_client_connector = new \Duzz\Base\Admin\Factory\Duzz_Forms_Connector('duzz_client');
    $duzz_forms_client_connector->duzz_add_section(self::duzz_settings_list_data('client_form_id'), 'form_id');
    $duzz_forms_client_connector->duzz_add_section(self::duzz_settings_list_data('client_field_numbers'), 'field_numbers');


    $duzz_forms_acf_values_connector = new \Duzz\Base\Admin\Factory\Duzz_Forms_Connector('duzz_acf_values');
    $duzz_forms_acf_values_connector->duzz_add_section( self::$field_data, 'acf_keys_list');

    $duzz_forms_settings_fields_connector = new \Duzz\Base\Admin\Factory\Duzz_Forms_Connector('duzz_settings');
    $duzz_forms_settings_fields_connector->duzz_add_section(self::duzz_settings_list_data('list_projects'), 'list_projects', 'select2', $removedKeys);

    $duzz_forms_settings_fields_connector->duzz_add_section(self::duzz_settings_list_data('email_settings'), 'email_settings', 'text');
    $duzz_forms_settings_fields_connector->duzz_add_section(self::duzz_settings_list_data('welcome_message'), 'welcome_message', 'textarea');
    
    $duzz_forms_settings_fields_connector->duzz_add_section(self::duzz_settings_list_data('toggle_redirect'), 'toggle_redirect', 'toggle');

    $duzz_forms_settings_fields_connector->duzz_add_section(self::duzz_settings_list_data('acf_group'), 'acf_group', 'text');
    $duzz_forms_settings_fields_connector->duzz_add_section(self::duzz_settings_list_data('remove_keys'), 'remove_keys', 'text');
    $duzz_forms_settings_fields_connector->duzz_add_section(self::duzz_settings_list_data('project_page'), 'project_page', 'select2', self::$field_filtered_data);


    $duzz_forms_stripe_keys_connector = new \Duzz\Base\Admin\Factory\Duzz_Forms_Connector('duzz_payment_settings');
    $duzz_forms_stripe_keys_connector->duzz_add_section(self::duzz_settings_list_data('stripe_keys_data'), 'stripe_keys', 'text');
    $duzz_forms_stripe_keys_connector->duzz_add_section(self::duzz_settings_list_data('stripe_test_toggle'), 'stripe_toggle', 'toggle');
    $duzz_forms_stripe_keys_connector->duzz_add_section(self::duzz_settings_list_data('stripe_test_keys'), 'stripe_test', 'text');

      
    $duzz_forms_stripe_keys_connector->duzz_init();
    $duzz_forms_admin_connector->duzz_init();
    $duzz_forms_client_connector->duzz_init();
    $duzz_forms_acf_values_connector->duzz_init();
    $duzz_forms_settings_fields_connector->duzz_init();
}


public static function duzz_forms_stripe_keys_connector_callback() {
    // Check user capabilities
    if (!current_user_can('activate_plugins')) {
        return;
    }

    global $duzz_forms_stripe_keys_connector;
    $option_group = $duzz_forms_stripe_keys_connector->duzz_get_option_group();
    $page_slug = $duzz_forms_stripe_keys_connector->duzz_get_page_slug();
    $duzz_forms_stripe_keys_connector->duzz_output_form($option_group, $page_slug);
}

public static function duzz_forms_admin_connector_callback() {
    // Check user capabilities
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }
  
    global $duzz_forms_admin_connector;

    $option_group = $duzz_forms_admin_connector->duzz_get_option_group();
    $page_slug = $duzz_forms_admin_connector->duzz_get_page_slug();
 
    $duzz_forms_admin_connector->duzz_output_form( $option_group, $page_slug );
}

public static function duzz_forms_client_connector_callback() {
    // Check user capabilities
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    global $duzz_forms_client_connector;
        $option_group = $duzz_forms_client_connector->duzz_get_option_group();
        $page_slug = $duzz_forms_client_connector->duzz_get_page_slug();

    $duzz_forms_client_connector->duzz_output_form( $option_group, $page_slug );
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

public static function duzz_forms_admin_settings_connector_callback() {
    // Check user capabilities
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    global $duzz_forms_settings_fields_connector;
    $option_group = $duzz_forms_settings_fields_connector->duzz_get_option_group();
    $page_slug = $duzz_forms_settings_fields_connector->duzz_get_page_slug();

    return $duzz_forms_settings_fields_connector->duzz_output_form( $option_group, $page_slug );
}





public static function duzz_settings_list_data($listType) {
    switch ($listType) {

        case 'stripe_keys_data':
            return array(
                'API_secret_key_live' => '',
                'API_publishable_key_live' => '',
            );
        break;


        case 'toggle_redirect':
            return array(
                'redirect_to_workspace_on_login' => '',
            );
        break;

        case 'stripe_test_toggle':
            return array(
                'Stripe_Test_API' => '',
            );
        break;

        case 'stripe_test_keys':
            return array(
                'API_secret_key_test' => '',
                'API_publishable_key_test' => '',
            );
        break;

        case 'acf_group':
            return array(
                'ACF_Group_ID_1' => 9927,
                'ACF_Group_ID_2' => 9961,
                'ACF_Group_ID_3' => 9965,
            );

        case 'remove_keys':
            return array(
                'ACF_Key_1' => 'field_6399e6ff0cf58',
                'ACF_Key_2' => 'field_61eead1406b70',
                'ACF_Key_3' => 'field_643b0cfbcf72c',
            );
        case 'admin_form_id':
            return array(
                'form_id' => 9933,
            );
        case 'client_form_id':
            return array(
                'form_id' => 9937,
            );

        case 'list_projects':
                return array(
                    'selected_columns_data_title' => array( 'customer_name'),
                    'selected_columns' => array( 'customer_email', 'website'),
                );
        
        case 'email_settings':
            return array(
                'admin_email' => 'admin@stark.io',
                'admin_name' => 'Tony Stark',
                'company_name' => 'Stark Industries',
            );

        case 'project_page':
                return array(
                    'main_data' => array('website'),
                    'header_data' => array('customer_phone', 'customer_email', 'approved_status', 'last_updated'),
                    'info_tab_fields' => array('project_status', 'approved_status', 'staff_notes', 'website'),
                    'updates_tab_fields' => array('customer_email', 'customer_phone', 'customer_address', 'customer_name'),
                );          
        case 'welcome_message':
           return array(
               'message' => 'welcome! How can we help you?',
           );


        case 'admin_field_numbers':
            return array(
                'customer_address' => 11,
                'customer_name'    => 19,
                'customer_email'   => 20,
                'customer_phone'   => 21,
                'website'          => 26,
                'staff_id'         => 2,
                'team_id'          => 24,
                'company_id'       => 25,
                'company_name'     => 27,
            );
        case 'client_field_numbers':
            return array(
                'customer_ip'      => 45,
                'customer_address' => 41,
                'customer_name'    => 1,
                'customer_email'   => 3,
                'customer_phone'   => 9,
                'website'          => 49,
                'staff_id'         => 46,
                'team_id'          => 47,
                'company_id'       => 48,
            );
        default:
            return array();
    }
}

}
