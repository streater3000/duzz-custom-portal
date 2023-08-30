<?php

namespace Duzz\Base\Admin;

class Duzz_ACF_Sync {
    
    public function __construct() {
        add_action('update_option_acf_values_acf_keys_list_field_data', array($this, 'run_on_option_update'), 10, 3);
    }

    public function run_on_option_update($old_value, $new_value, $option) {
        if (is_plugin_active('advanced-custom-fields/acf.php')) {
            $this->create_acf_field_group_and_field();
        }
    }

    public function create_acf_field_group_and_field() {
        if (post_type_exists('acf-field-group') && post_type_exists('acf-field')) {
            // Check if the field group already exists
            $existing_group = get_page_by_title('Duzz Fields', OBJECT, 'acf-field-group');

            if (null === $existing_group) {
                $group_id = wp_insert_post([
                    'post_type' => 'acf-field-group',
                    'post_title' => 'Duzz Fields',
                    'post_status' => 'publish',
                ]);
            } else {
                $group_id = $existing_group->ID;
            }

            // Assuming Duzz_Keys::$keys exists and is accessible here.
            $keys = \Duzz\Utils\Duzz_Keys::$keys;

            foreach ($keys as $name => $key) {
                // Avoid duplicating fields within the group
                $existing_field = get_page_by_title(ucwords(str_replace('_', ' ', $name)), OBJECT, 'acf-field');

                if (null === $existing_field) {
                    $field_id = wp_insert_post([
                        'post_type' => 'acf-field',
                        'post_title' => ucwords(str_replace('_', ' ', $name)),
                        'post_name' => $key,
                        'post_excerpt' => $name,
                        'post_status' => 'publish',
                        'post_parent' => $group_id,
                    ]);

                    update_post_meta($field_id, 'field_type', 'text');
                    update_post_meta($field_id, 'field_key', $key);
                    update_post_meta($field_id, 'field_name', $name);
                }
            }
        }
    }
}

