<?php


namespace Duzz\Utils;

/**
 * Accessing meta values by their unique field key is more consistent than using the field name.
 * But they are not as human-readable. So this class provides a way to use the human readable name
 * but get the benefits of using the field key.
 */

class Duzz_Keys {
    public static $keys = [
            'team_id'                   => 'field_8a90f9d230c74e',
            'company_id'                => 'field_89b0c7a238e61f',
            'staff_id'                  => 'field_8a09e2d134b70e',
            'customer_id'               => 'field_8a90b1c738d69b',
            'archived'                  => 'field_8b09a8c634d70a',
            'approved_status'           => 'field_8c08e7f539b61d',
            'project_status'            => 'field_8a80d7e537a69e',
            'customer_status'           => 'field_8d90e8b536c60c',
            'staff_notes'               => 'field_8a60c9a538b61f',
            'customer_first_name'       => 'field_8a20f7c839a78b',
            'customer_last_name'        => 'field_8e60b8d536a69a',
            'customer_phone'            => 'field_8a40d7f538b69c',
            'customer_email'            => 'field_8e50c8e536d60d',
            'company_name'              => 'field_8f40b9f539a68b',
            'website'                   => 'field_8a50e8d538b60f',
            'customer_address'          => 'field_8f50b9f539a60c',
            'customer_address1'         => 'field_8e30b8d536d61e',
            'customer_address2'         => 'field_8d20c7e537a78d',
            'customer_city'             => 'field_8a30f7f539a69f',
            'customer_state'            => 'field_8a10d6d538b60d',
            'customer_zip'              => 'field_8c30c7e536d69e',
            'files_customer_uploaded'   => 'field_8d40b8d538b68c',
            'files_staff_uploaded'      => 'field_8a60f9f539a69c',
            'last_updated'              => 'field_8a80c7d537a68d',
            'pricing_plan'              => 'field_8a40d7e538b68f',
            'customer_ip'               => 'field_8e40b9c536d68e',
            'customer_name'             => 'field_8a70d8e538b61a',
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
