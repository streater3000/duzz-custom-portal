<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML\Duzz_ACF_Field;
use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;
use Duzz\Shared\Actions as ActionsNamespace;
use Duzz\Core\Duzz_Get_Data;

class Duzz_Fields_Factory {
    public $selected_fields;
    public $fallback;

public function __construct($selected_fields, $fallback = 'TBD') {
    $project_id = get_query_var('project_id', false);

    if (!$project_id) {
        // Handle the project when project_id is not available, e.g., return an error message or an empty Div.
        return;
    }

    $this->project_id = absint($project_id);
    $this->selected_fields = $selected_fields;
    $this->fallback = $fallback;
}

public function duzz_render_acf_button() {
    // Check if ACF is enabled
    if(function_exists('acf_form_head')) {
        $button_html = '<input type="submit" class="acf-button button button-primary button-large" value="Save Changes" onclick="updateFormActionAndSubmit(event);">';
    } else {
        $button_html = '<input type="submit" class="button button-primary button-large" value="Save Changes">';
    }

    $button_container = new Duzz_Return_HTML('div', array('class' => 'acf-form-container'));
    $button_container->duzz_addChild('div')->duzz_setContent($button_html);

    return $button_container;
}


    public function duzz_combined_address() {
        $address_fields = array(
            'customer_address1',
            'customer_address2',
            'customer_city',
            'customer_state',
            'customer_zip',
        );

        $address_container = new Duzz_Return_HTML('div', array('class' => 'address-container'));


            foreach ($address_fields as $field_name) {
  
                    $this->title = ActionsNamespace\Duzz_Format_Label::duzz_format($field_name);
                    $duzz_address_field = new Duzz_Return_HTML('div', array('class' => 'duzz-address-field'));

                    $css_class_name = str_replace('_', '-', $field_name);
                    $custom_width_container = new Duzz_Return_HTML('div', array('class' => $css_class_name . '-width duzz-address-field'));
                    $field_instance = new Duzz_ACF_Field($this->project_id, $field_name);
                    $custom_width_container->duzz_setContent($field_instance->duzz_render());

                    $address_container->duzz_addChild($custom_width_container);
                
            }
        

        $div_address_container = new Duzz_Return_HTML('div', array('class' => 'column-account address-fields-width'));
        $AddressTitle = new Duzz_Return_HTML('div', array('class' => 'account-titles'));
        $AddressTitle->duzz_addChild('div')->duzz_setContent('Address');
        $div_address_container->duzz_addChild($AddressTitle);
        $div_address_container->duzz_addChild($address_container);

        return $div_address_container;
    }

    public function duzz_render_all_fields() {
        $container = new Duzz_Return_HTML('div', array('class' => 'fields-factory-container'));

        $container = new Duzz_Return_HTML('form', array(
            'id' => 'post',
            'method' => 'post'
        ));

if (!function_exists('acf_form_head')) {
    $container->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'post-id-pass-post-nonce',
        'value' => wp_create_nonce('post_id_action_pass_post_nonce')
    ));

    // Add hidden input for post_id if needed
    $container->duzz_addChild('input', array(
        'type' => 'hidden',
        'name' => 'post_id',
        'value' => $this->project_id
    ));
}

$container->duzz_addChild('input', array(
    'type' => 'hidden',
    'name' => 'selected_fields',
    'value' => implode(',', $this->selected_fields)
));

        $fields_container = new Duzz_Return_HTML('div', array('class' => 'editable-selected-fields-container'));
        $container->duzz_addChild($fields_container);

        $removed_address_fields = new ActionsNamespace\Duzz_RemoveFields();
        $fields_to_remove = $removed_address_fields->duzz_get_filtered_fields();
        $selected_fields_without_customer_address = array_diff($this->selected_fields, $fields_to_remove);

        if (in_array('customer_address', $this->selected_fields)) {
            foreach ($selected_fields_without_customer_address as $selected_field) {
                $this->field_name = $selected_field;
                $this->title = ActionsNamespace\Duzz_Format_Label::duzz_format($this->field_name);
                $fields_container->duzz_addChild($this->duzz_render_single_field(false));
            }
            $fields_container->duzz_addChild($this->duzz_combined_address());
        } else {

            foreach ($this->selected_fields as $selected_field) {
                $this->field_name = $selected_field;
                $this->title = ActionsNamespace\Duzz_Format_Label::duzz_format($this->field_name);
                $fields_container->duzz_addChild($this->duzz_render_single_field(false));
            }
        }

        $button_and_message_wrapper = new Duzz_Return_HTML('div', array('class' => 'button-and-message-wrapper'));
        $updated_message_div = new Duzz_Return_HTML('div', array('class' => 'acf-updated-message'));

        if (get_query_var('updated', false) === 'true') {
            $updated_message_div->duzz_addChild('div')->duzz_setContent('Updated Project');
        } else {
            $updated_message_div->duzz_setContent('');
        }


        $button_and_message_wrapper->duzz_addChild($this->duzz_render_acf_button());
        $button_and_message_wrapper->duzz_addChild($updated_message_div);
        $container->duzz_addChild($button_and_message_wrapper);

        return $container;
    }

  public function duzz_render_single_field($with_container = true) {
        $title_div = new Duzz_Return_HTML('div', array('class' => 'account-titles'));
        $title_div->duzz_setContent($this->title);

        $css_class_name = str_replace('_', '-', $this->field_name);
        $custom_width_container = new Duzz_Return_HTML('div', array('class' => $css_class_name . '-width'));

        $field_instance = new Duzz_ACF_Field($this->project_id, $this->field_name);
        $custom_width_container->duzz_setContent($field_instance->duzz_render());
        $container = new Duzz_Return_HTML('div', array('class' => 'fields-column-account'));
        $container->duzz_addChild($title_div);
        $container->duzz_addChild($custom_width_container);

        if ($with_container) {
            $div_container = new Duzz_Return_HTML('div', array('class' => 'acf-width-flex'));
            $div_container->duzz_addChild($container);
            return $div_container;
        }

        return $container;
    }
}
