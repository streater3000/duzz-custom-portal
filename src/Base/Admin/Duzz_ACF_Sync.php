<?php

namespace Duzz\Base\Admin;

class Duzz_ACF_Sync {

    public function __construct() {
        add_action('update_option_duzz_acf_settings_acf_keys_list_field_data', array($this, 'duzz_run_on_option_update'), 10, 3);
        // Set a very high priority to delay the script enqueue
        add_action('admin_enqueue_scripts', array($this, 'duzz_enqueue_generate_assets'), PHP_INT_MAX);
    }

        public function setFieldData($field_data) {
        $this->field_data = $field_data;
    }

public function duzz_enqueue_generate_assets() {
        if (is_admin()) {
            wp_enqueue_script('jquery');

            // You can still localize the script if you need to pass other data like nonce or ajaxurl
            wp_localize_script('jquery', 'duzzACFData', array(
                'nonce' => wp_create_nonce('message_id_action_pass_post_nonce'),
                'ajaxurl' => admin_url('admin-ajax.php')
            ));

            // Add updated inline script
            wp_add_inline_script('jquery', "
                jQuery(document).ready(function($) {
                    console.log('Admin script loaded');

                    // Click handler for #generate-fields-button
                    $(document).on('click', '#generate-fields-button', function() {
                        console.log('Generate fields button clicked');

                        // Retrieve data from the hidden field
                        var defaultValues = $('#default-values-data').val();
                        if (defaultValues) {
                            var fieldData = JSON.parse(defaultValues);
                            console.log('Field data:', fieldData);

                            // Populate the fields
                            $.each(fieldData, function(key, value) {
                                console.log('Setting value for field', key, 'to', value);
                                $('#' + key).val(value);
                            });
                        } else {
                            console.error('No default values found');
                        }
                    });
                });
            ", 'after', DUZZ_PLUGIN_VERSION);
        }
    }


    public function duzz_run_on_option_update($old_value, $new_value, $option) {
        if (is_plugin_active('advanced-custom-fields/acf.php')) {
            $this->duzz_create_acf_field_group_and_field();
        }
    }

     public function duzz_create_acf_field_group_and_field() {
        // Check if the ACF field group already exists
        if ($this->duzz_acf_field_group_exists('group_oi498s89f43')) {
            return; // Exit the function if the group already exists
        }

        // Proceed to create field group and fields if it doesn't exist
        $group_id = wp_insert_post([
            'post_type' => 'acf-field-group',
            'post_title' => 'Duzz Fields',
            'post_status' => 'publish',
            'post_name' => 'group_oi498s89f43',
            'import_id'  => 9900,
        ]);

        $keys = \Duzz\Utils\Duzz_Keys::duzz_get_updated_keys();

        foreach ($keys as $name => $key) {
            $args_field = [
                'post_type' => 'acf-field',
                'name' => $key,
                'posts_per_page' => 1,
            ];

            $query_field = new \WP_Query($args_field);

            if (!$query_field->have_posts()) {
                wp_insert_post([
                    'post_type' => 'acf-field',
                    'post_title' => ucwords(str_replace('_', ' ', $name)),
                    'post_name' => $key,
                    'post_excerpt' => $name,
                    'post_status' => 'publish',
                    'post_parent' => $group_id,
                ]);
            }

            wp_reset_postdata();
        }
    }

    private function duzz_acf_field_group_exists($group_slug) {
        $args = [
            'post_type' => 'acf-field-group',
            'name' => $group_slug,
            'posts_per_page' => 1
        ];

        $query = new \WP_Query($args);
        return $query->have_posts();
    }
}
