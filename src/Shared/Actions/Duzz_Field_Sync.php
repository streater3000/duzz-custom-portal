<?php

namespace Duzz\Shared\Actions;

use Duzz\Utils\Duzz_Keys;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Core\Duzz_Helpers;

class Duzz_Field_Sync {

    function __construct() {

        add_action('acf/save_post', [$this, 'duzz_sync_customer_name_and_address_fields'], 20);
        add_action('save_post', [$this, 'duzz_sync_customer_name_and_address_fields'], 20);
        add_action('duzz_fields_updated', [$this, 'duzz_sync_customer_name_and_address_fields'], 20);
        add_filter('acf/prepare_field', [$this, 'duzz_make_individual_name_and_address_fields_read_only']);
    }

    function duzz_sync_customer_name_and_address_fields($post_id) {


        if (get_post_type($post_id) !== 'project') {
            return;
        }

        $website = Duzz_Helpers::duzz_get_field('website', $post_id);
        $customer_email = Duzz_Helpers::duzz_get_field('customer_email', $post_id); 

        $formatted_website = Duzz_Format_Label::duzz_format_website($website);
        $formatted_email = Duzz_Format_Label::duzz_format_website($customer_email);  

        Duzz_Helpers::duzz_update_field('website', $formatted_website, $post_id);
        Duzz_Helpers::duzz_update_field('customer_email', $formatted_email, $post_id);


        $customer_name = Duzz_Helpers::duzz_get_field('customer_name', $post_id);

        if (is_string($customer_name)) {
               $formatted_names = Duzz_Format_Label::duzz_format_user_name($customer_name);

        Duzz_Helpers::duzz_update_field('customer_first_name', $formatted_names['first_name'], $post_id);
        Duzz_Helpers::duzz_update_field('customer_last_name', $formatted_names['last_name'], $post_id);
        Duzz_Helpers::duzz_update_field('customer_name', $formatted_names['customer_name'], $post_id);
        }

        $address1 = Duzz_Helpers::duzz_get_field('customer_address1', $post_id);
        $address2 = Duzz_Helpers::duzz_get_field('customer_address2', $post_id);
        $city = Duzz_Helpers::duzz_get_field('customer_city', $post_id);
        $state = Duzz_Helpers::duzz_get_field('customer_state', $post_id);
        $zip = Duzz_Helpers::duzz_get_field('customer_zip', $post_id);      

        $formatted_address = Duzz_Format_Label::duzz_format_address($address1, $address2, $city, $state, $zip);      

        Duzz_Helpers::duzz_update_field('customer_address1', $formatted_address['address1'], $post_id);
        Duzz_Helpers::duzz_update_field('customer_address2', $formatted_address['address2'], $post_id);
        Duzz_Helpers::duzz_update_field('customer_city', $formatted_address['city'], $post_id);
        Duzz_Helpers::duzz_update_field('customer_state', $formatted_address['state'], $post_id);
        Duzz_Helpers::duzz_update_field('customer_zip', $formatted_address['zip'], $post_id);
        Duzz_Helpers::duzz_update_field('customer_address', $formatted_address['customer_address'], $post_id);      

    }

    function duzz_make_individual_name_and_address_fields_read_only($field) {


        $read_only_fields = [
            Duzz_Keys::duzz_get_acf_key('customer_first_name'),
            Duzz_Keys::duzz_get_acf_key('customer_last_name'),
            Duzz_Keys::duzz_get_acf_key('customer_address'),
        ];

        $field_key = $field['key'];

        if (in_array($field_key, $read_only_fields)) {

            $field['readonly'] = 1;
            $field['disabled'] = 1;
        }

        return $field;
    }
}
