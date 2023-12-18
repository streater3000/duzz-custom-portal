<?php

namespace Duzz\Shared\Layout\HTML;

use Duzz\Utils\Duzz_Keys;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;

class Duzz_ACF_Field {
    private $project_id;
    private $field_key;
    private $user;

    public function __construct($project_id, $field_name) {
        $this->project_id = $project_id;
        $this->user = wp_get_current_user();
        $this->field_name = $field_name;
        $this->field_key = Duzz_Keys::duzz_get_saved_acf_key($this->field_name);
    }

public function duzz_custom_remove_acf_labels($field, $add_placeholder = true) {
    $field['label'] = '';

    if ($add_placeholder) {
        // Get the field name by ACF key
        $field_name = Duzz_Keys::duzz_get_field_name_by_acf_key($field['key']);

        // Add a placeholder text based on the field name
        $placeholder_text = ucwords(str_replace('_', ' ',  $this->field_name));

        // Remove the word 'Customer' from the placeholder text
        $placeholder_text = str_replace('Customer ', '', $placeholder_text);

        $field['placeholder'] = $placeholder_text;

    }

    return $field;
}

    
public function duzz_render($add_placeholder = true) {

if (function_exists('acf_form_head')) {
    if (count(array_intersect($this->user->roles, ['administrator', 'duzz_admin'])) === 0) {
        return;
    }

    ob_start();

    acf_form_head();

    $args = array(
        'post_id' => $this->project_id,
        'fields' => [$this->field_key], // Wrap the field_key in an array
        'updated_message' => '', // Set the updated_message to an empty string
        'form' => false, // Disable the default form wrapper and submit button
        'html_submit_spinner' => '', // Remove the submit spinner
    );

add_filter('acf/prepare_field', function ($field) use ($add_placeholder) {
    // Add your CSS class here
    $field['wrapper']['class'] .= ' acf-fields-width';
    return $this->duzz_custom_remove_acf_labels($field, $add_placeholder);
});

    acf_form($args);
    remove_filter('acf/prepare_field', function ($field) use ($add_placeholder) {
        return $this->duzz_custom_remove_acf_labels($field, $add_placeholder);
    });


    $html = ob_get_contents();
    ob_end_clean();

    return $html;
        } else {
            // If ACF is not active, use Duzz_Return_HTML to create fields
            $field_value = get_post_meta($this->project_id, $this->field_name, true);

            $form = new Duzz_Return_HTML('div', array('class' => 'duzz-custom-field-container'));

            $label = ucwords(str_replace('_', ' ',  $this->field_name));


            $form->duzz_addChild('input', array(
                'type' => 'text',
                'name' => $this->field_name,
                'id' => $this->field_name,
                'value' => $field_value,
                'class' => 'duzz-basic-fields',
                'placeholder' => $label
            ));
    return $form;
        }
    }
}