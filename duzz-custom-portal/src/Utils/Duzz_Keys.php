<?php


namespace Duzz\Utils;

/**
 * Accessing meta values by their unique field key is more consistent than using the field name.
 * But they are not as human-readable. So this class provides a way to use the human readable name
 * but get the benefits of using the field key.
 */

class Duzz_Keys {
    public static $keys = [
            'team_id'                   => 'field_620e6fd9c604d',
            'company_id'                => 'field_620e6fb1c604b',
            'staff_id'             => 'field_61e6c744d8654',
            'customer_id'               => 'field_61e7dc762085b',
            'archived' => 'field_620e51353811a',
            'approved_status'           => 'field_61e6b15c3354c',
            'project_status'               => 'field_61e6b15c33555',
            'customer_status'           => 'field_61e6c744d8634',
            'staff_notes'          => 'field_61e971aa9c3aa',
            'customer_first_name'        => 'field_61e6c744d865a',
            'customer_last_name'        => 'field_61e94ff6a3073',
            'customer_phone'            => 'field_61e6c744d861c',
            'customer_email'            => 'field_61e6c744d8628',
            'company_name'            => 'field_643c024f3ca6e',
            'website'                    => 'field_6446c5020cff5',
            'customer_address'          =>  'field_643b0cfbcf72c',
            'customer_address1'           => 'field_61e7e039cdec5',
            'customer_address2'           => 'field_61e7e049cdec6',
            'customer_city'               => 'field_61e7e060cdec7',
            'customer_state'              => 'field_61e7e075cdec8',
            'customer_zip'                => 'field_61e7e078cdec9',
            'files_customer_uploaded'   => 'field_61e7f69f53cc6',
            'files_staff_uploaded' => 'field_61e7f6b653cc7',
            'last_updated'              => 'field_61eead1406b70',
            'pricing_plan'              => 'field_61e96e48bd79e',            
            'customer_ip'        => 'field_6399e6ff0cf58',
            'customer_name'     =>  'field_643b11b4043a1',
        ];


public static function get_filtered_fields() {
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

public static function get_keys_removed($keys_to_remove = []) {
    // Initialize the keys
    self::init();

    // Get all the keys
    $keys = self::get_keys();

    // Get the filtered fields from get_filtered_fields method
    $filtered_fields = self::get_filtered_fields();

    // Merge both arrays to get a union of keys to remove
    $all_keys_to_remove = array_merge($filtered_fields, $keys_to_remove);

    // Filter the keys
    $filtered_keys = array_diff_key($keys, array_flip($all_keys_to_remove));

    // Return the filtered keys
    return $filtered_keys;
}

    public static function get_filtered_keys() {
        // Initialize the keys
        self::init();

        // Get the filtered fields
        $filtered_fields = self::get_filtered_fields();

        // Get the keys
        $keys = self::get_keys();

        // Filter the keys
        $filtered_keys = array_diff_key($keys, array_flip($filtered_fields));

        // Return the filtered keys
        return $filtered_keys;
    }

public static function init() {
    global $duzz_forms_acf_values_connector;

    if(is_object($duzz_forms_acf_values_connector) && method_exists($duzz_forms_acf_values_connector, 'get_option_name')) {
        $option_name = $duzz_forms_acf_values_connector->get_option_name();
        $saved_keys = get_option($option_name, []);

        // Override the default keys with the saved keys
        foreach ($saved_keys as $field_name => $acf_key) {
            if (!empty($acf_key)) {
                self::$keys[$field_name] = $acf_key;
            }
        }
    }
}

   public static function get_keys() {
        return self::$keys;
    }


public static function get_field_name($acf_key) {
    // Search for the acf_key in the $keys array
    $field_name = array_search($acf_key, self::$keys);

    // If the field_name is found, return it; otherwise, return an empty string
    return $field_name !== false ? $field_name : '';
}

public static function get_field_name_by_acf_key($acf_key) {
    $field_name = array_search($acf_key, self::$keys);
    return $field_name !== false ? $field_name : '';
}


    public static function get_field_value($field_name, $post_id) {
        $acf_key = self::get_acf_key($field_name);
        if (!empty($acf_key)) {
            return duzz_get_field($acf_key, $post_id);
        } else {
            return '';
        }
    }

    public static function get_acf_key($field_name) {
        if (isset(self::$keys[$field_name])) {
            return self::$keys[$field_name];
        } else {
            return '';
        }
    }
}

// Call the init() function to initialize the class
// Move this line outside the class definition
// Keys::init();