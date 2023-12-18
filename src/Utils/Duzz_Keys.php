<?php


namespace Duzz\Utils;

/**
 * Accessing meta values by their unique field key is more consistent than using the field name.
 * But they are not as human-readable. So this class provides a way to use the human readable name
 * but get the benefits of using the field key.
 */

class Duzz_Keys {
    public static $keys = [
            'team_id'                   => ' ',
            'company_id'                => ' ',
            'staff_id'                  => ' ',
            'customer_id'               => ' ',
            'archived'                  => ' ',
            'approved_status'           => ' ',
            'project_status'            => ' ',
            'customer_status'           => ' ',
            'staff_notes'               => ' ',
            'customer_first_name'       => ' ',
            'customer_last_name'        => ' ',
            'customer_phone'            => ' ',
            'customer_email'            => ' ',
            'company_name'              => ' ',
            'website'                   => ' ',
            'customer_address'          => ' ',
            'customer_address1'         => ' ',
            'customer_address2'         => ' ',
            'customer_city'             => ' ',
            'customer_state'            => ' ',
            'customer_zip'              => ' ',
            'files_customer_uploaded'   => ' ',
            'files_staff_uploaded'      => ' ',
            'last_updated'              => ' ',
            'pricing_plan'              => ' ',
            'customer_ip'               => ' ',
            'customer_name'             => ' ',
            'customer_message'          =>  ' '
    ];


public static function duzz_get_filtered_fields() {
    // Replace this array with the actual fields you want to use.
    $fields = array(
            'customer_address1',
            'customer_address2',
            'customer_city',
            'customer_state',
            'customer_zip',
            'team_id',
            'staff_id',
            'company_id',
            'contractor_id',
            'customer_id',
            'files_customer_uploaded',
            'files_contractor_uploaded',
            'customer_ip',
            'status_feed',
            'customer_first_name',
            'customer_last_name',
            'files_staff_uploaded'
    );

    return $fields;
}

    public static function duzz_get_updated_keys() {
        $option_name = 'duzz_acf_settings_acf_keys_list_field_data';
        $saved_keys = get_option($option_name, []);

        if (!empty($saved_keys)) {
            // Replace the default keys with the saved ones
            foreach ($saved_keys as $field_name => $acf_key) {
                if (isset(self::$keys[$field_name])) {
                    self::$keys[$field_name] = $acf_key;
                }
            }
        }

        return self::$keys;
    }

    public static function duzz_get_saved_acf_key($field_name) {
        $option_name = 'duzz_acf_settings_acf_keys_list_field_data';
        $saved_keys = get_option($option_name, []);

        // Check if the field_name exists in the saved keys and return the ACF key
        if (!empty($saved_keys) && isset($saved_keys[$field_name])) {
            return $saved_keys[$field_name];
        } else {
            // If the field_name is not found, return an empty string
            return '';
        }
    }

public static function duzz_get_keys_removed($keys_to_remove = []) {
    // Initialize the keys
    self::duzz_init();

    // Get all the keys
    $keys = self::duzz_get_keys();

    // Get the filtered fields from get_filtered_fields method
    $filtered_fields = self::duzz_get_filtered_fields();

    // Merge both arrays to get a union of keys to remove
    $all_keys_to_remove = array_merge($filtered_fields, $keys_to_remove);

    // Filter the keys
    $filtered_keys = array_diff_key($keys, array_flip($all_keys_to_remove));

    // Return the filtered keys
    return $filtered_keys;
}

    public static function duzz_get_filtered_keys() {
        // Initialize the keys
        self::duzz_init();

        // Get the filtered fields
        $filtered_fields = self::duzz_get_filtered_fields();

        // Get the keys
        $keys = self::duzz_get_keys();

        // Filter the keys
        $filtered_keys = array_diff_key($keys, array_flip($filtered_fields));

        // Return the filtered keys
        return $filtered_keys;
    }

public static function duzz_init() {
    global $duzz_forms_acf_values_connector;

    if(is_object($duzz_forms_acf_values_connector) && method_exists($duzz_forms_acf_values_connector, 'duzz_get_option_name')) {
        $option_name = $duzz_forms_acf_values_connector->duzz_get_option_name();
        $saved_keys = get_option($option_name, []);

        // Override the default keys with the saved keys
        foreach ($saved_keys as $field_name => $acf_key) {
            if (!empty($acf_key)) {
                self::$keys[$field_name] = $acf_key;
            }
        }
    }
}

   public static function duzz_get_keys() {
        return self::$keys;
    }


public static function duzz_get_field_name($acf_key) {
    // Search for the acf_key in the $keys array
    $field_name = array_search($acf_key, self::$keys);

    // If the field_name is found, return it; otherwise, return an empty string
    return $field_name !== false ? $field_name : '';
}

public static function duzz_get_field_name_by_acf_key($acf_key) {
    $field_name = array_search($acf_key, self::$keys);
    return $field_name !== false ? $field_name : '';
}


    public static function duzz_get_field_value($field_name, $post_id) {
        $acf_key = self::duzz_get_acf_key($field_name);
        if (!empty($acf_key)) {
            return duzz_get_field($acf_key, $post_id);
        } else {
            return '';
        }
    }

    public static function duzz_get_acf_key($field_name) {
        if (isset(self::$keys[$field_name])) {
            return self::$keys[$field_name];
        } else {
            return '';
        }
    }
}
