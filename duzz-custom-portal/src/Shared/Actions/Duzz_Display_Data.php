<?php

namespace Duzz\Shared\Actions;

use Duzz\Utils\Duzz_Keys;
use Duzz\Core\Duzz_Helpers;

class Duzz_Display_Data {

    private $field;
    private $fallback;

    public function __construct($field, $fallback = '') {
        $this->field = sanitize_text_field($field);
        $this->fallback = sanitize_text_field($fallback);
    }

    public function get_display_data() {
        if (!isset($_GET['project_id'])) {
            return '';
        }

        // Sanitize
        $project_id = sanitize_text_field($_GET['project_id']);
        
        // Validate
        if (!Duzz_Validate_ID::validate($project_id)) {
            return ''; // return empty if invalid
        }

        $project_id = absint($project_id);
        
        if (empty($this->field)) {
            return '';
        }

        $raw_value = '';

        if ($this->field == 'customer_name') {
            $first_name  = Duzz_Helpers::duzz_get_field('customer_first_name', $project_id) ?: '';
            $last_name   = Duzz_Helpers::duzz_get_field('customer_last_name', $project_id) ?: '';
            $field_value = trim($first_name . ' ' . $last_name);
        } elseif ($this->field == 'customer_address') {
            $address = [
                'address1' => Duzz_Helpers::duzz_get_field('customer_address1', $project_id),
                'address2' => Duzz_Helpers::duzz_get_field('customer_address2', $project_id),
                'city'     => Duzz_Helpers::duzz_get_field('customer_city', $project_id),
                'state'    => Duzz_Helpers::duzz_get_field('customer_state', $project_id),
                'zip'      => Duzz_Helpers::duzz_get_field('customer_zip', $project_id),
            ];

            $field_value = implode(', ', array_map('sanitize_text_field', array_filter($address)));
        } else {
            $raw_value = Duzz_Helpers::duzz_get_field($this->field, $project_id) ?: '';
        }

        if (is_array($raw_value)) {
            $field_value = implode(', ', array_map('sanitize_text_field', array_filter($raw_value)));
        } else {
            $field_value = sanitize_text_field($raw_value);
        }

        $output = $field_value ?: $this->fallback;

        // Escaping the output
        $html = '<p class="field-' . esc_attr($this->field) . '">';
        $html .= wp_kses_post(nl2br($output));
        $html .= '</p>';

        if (property_exists('Keys', $this->field)) {
            $raw_value = Duzz_Helpers::duzz_get_field($this->field, $project_id) ?: '';
        }

        return $html;
    }
}
