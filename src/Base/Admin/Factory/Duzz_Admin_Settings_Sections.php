<?php


namespace Duzz\Base\Admin\Factory;

use Duzz\Shared\Layout\HTML as HTMLNamespace;
use Duzz\Shared\Actions as ActionsNamespace;

class Duzz_Admin_Settings_Sections {
    private $field_data;
    private $page_name;
    private $section_name;
    private $has_duplicates = true;
    private $forms_connector;
    private $form_type;
    private $maxSelections;

    public function __construct(array $field_data, string $page_name, string $section_name, Duzz_Forms_Connector $forms_connector, $form_type, $generate_fields, $available_selections) { // Modify this line
        $this->field_data = $field_data;
        $this->page_name = $page_name;
        $this->section_name = $section_name;
        $this->forms_connector = $forms_connector; 
        $this->form_type = $form_type;
        $this->available_selections = $available_selections;
        $this->generate_fields = $generate_fields;
    }

    public function duzz_init() {
        add_action( 'admin_init', array( $this, 'duzz_register_settings' ) );
    }


public function duzz_display_settings_saved_notice() {
    $settings_updated = filter_input(INPUT_GET, 'settings-updated', FILTER_VALIDATE_BOOLEAN);
    if ($settings_updated) {
        $div = new HTMLNamespace\Duzz_HTML('div', ['class' => 'notice notice-success is-dismissible']);
        $paragraph = new HTMLNamespace\Duzz_HTML('p');
        $paragraph->duzz_addChild(__('Settings saved.', 'your-textdomain'));

        $div->duzz_addChild($paragraph);

        return $div->duzz_render();
    }
}

    public function duzz_register_settings() {
        $default_field_data = $this->field_data;

        // Register the settings
        register_setting(
            $this->forms_connector->duzz_get_option_group(), // Option group
            $this->duzz_get_option_name(), // Option name
            [ $this, 'duzz_forms_sanitize_field_data_callback' ]
        );

        // Add the settings section
        add_settings_section(
            $this->duzz_get_section_id(), // Section ID
            'Field Numbers', // Section title
            null, // Section callback
            $this->forms_connector->duzz_get_page_slug()  // Page slug
        );


        // Add the settings fields
        foreach ( $default_field_data as $field_name => $default_value ) {
            add_settings_field(
                $field_name, // Field ID
                ucwords(str_replace('_', ' ', $field_name)), // Field title
                [ $this, 'duzz_forms_admin_connector_field_callback' ], // Field callback
                $this->forms_connector->duzz_get_page_slug(),  // Page slug
                $this->duzz_get_section_id(), // Section ID
                array( 'label_for' => $field_name ) // Field arguments
            );
        }
    }

public function duzz_render_form_table(string $page_slug) {
    $this->duzz_display_settings_saved_notice(); 

    $saved_field_data = get_option($this->duzz_get_option_name());
    $default_field_data = $this->field_data;


    $field_type = $this->form_type; // Store the field type in a local variable
    $section_name = $this->section_name;
    $table = new Duzz_Admin_Table_Form_Factory($field_type, $section_name);


    foreach ($default_field_data as $field_name => $default_value) {
        if (is_array($default_value)) {
            $maxSelections = count($default_value);
        }
            // Get the saved value or default value for the field

if ($this->generate_fields) {
        $field_value = isset($saved_field_data[$field_name]) ? $saved_field_data[$field_name] : null;
} else {
        $field_value = isset($saved_field_data[$field_name]) ? $saved_field_data[$field_name] : $default_value;
}
        $field_type = $this->form_type;
        $options_name = $this->duzz_get_option_name();
        $nameAttribute = "{$options_name}[{$field_name}]";

        switch ($field_type) {
                    case 'select2':
                        $field = esc_html(ActionsNamespace\Duzz_Format_Label::duzz_format($field_name));
                        $select = new HTMLNamespace\Duzz_Select2([
                            'name' => $nameAttribute . '[]',
                            'id' => $field_name,
                            'style' => 'width: 100%',
                            'class' => 'custom-dropdown',
                            'data-max-selections' => $maxSelections,
                            'multiple' => 'multiple'
                        ], $this->available_selections, $field_value);

                        ob_start();
                            $select->duzz_render();
                            $select = ob_get_clean();
                            $table->duzz_addRow([$field, $select]);
                            break;
                case 'toggle':
                    $field = esc_html(ActionsNamespace\Duzz_Format_Label::duzz_format($field_name));
                    $toggle_attributes = array(
                        'name' => $nameAttribute,
                        'value' => '1', 
                    );
                    
                    $is_toggle_checked = ($field_value == '1');
                    $toggle = new HTMLNamespace\Duzz_Toggle($toggle_attributes, $is_toggle_checked);
                    ob_start();
                    $toggle->duzz_render();
                    $toggle = ob_get_clean();
                    $table->duzz_addRow([$field, $toggle]);
                    break;
                 case 'text':
                    $field = esc_html(ActionsNamespace\Duzz_Format_Label::duzz_format($field_name));
                    $fieldObj = new HTMLNamespace\Duzz_Field('input', [
                        'name' => $nameAttribute,
                        'value' => $field_value,
                        'id' => $field_name,
                        'type' => $field_type
                         ]);
                    ob_start();
                    $fieldObj->duzz_render();
                    $bufferedFieldObj = ob_get_clean();
                    $table->duzz_addRow([$field, $bufferedFieldObj]);
                    break;
                case 'textarea':
                    $field = esc_html(ActionsNamespace\Duzz_Format_Label::duzz_format($field_name));
                    $fieldObj = new HTMLNamespace\Duzz_Field('textarea', [
                        'name' => $nameAttribute,
                        'id' => $field_name
                     ], $field_value);
                    ob_start();
                    $fieldObj->duzz_render();
                    $bufferedFieldObj = ob_get_clean();
                    $table->duzz_addRow([$bufferedFieldObj]);
                break;

                case 'table':
                    // Create the table column values
                    $column1 = esc_html(ActionsNamespace\Duzz_Format_Label::duzz_format($field_name));
                    $column2 = $field_name;
                    $column3 = new HTMLNamespace\Duzz_Field('input',[
                        'name' => $nameAttribute,
                        'value' => $field_value,
                        'id'    =>  $field_name
                    ]);
                // Check for duplicate field numbers and display error message
                if (Duzz_Duplicate_Check::duzz_has_duplicate_value($field_value, $saved_field_data)) {
                    $errorHtml = '<span class="field-error-message" style="color: red;">Error: Duplicate field number found.</span>';
                    ob_start();
                    $column3->duzz_render();
                    $column3Html = ob_get_clean();
                    $column3 = $column3Html . $errorHtml;
                } else {
                    ob_start();
                    $column3->duzz_render();
                    $column3 = ob_get_clean();
                }

                    // Add the columns to the table
                    $table->duzz_addRow([$column1, $column2, $column3]);
                    break;
                // Add more case statements for other field types
            }

        }

    $fields_table_html = $table->duzz_render();


$button_table_html = '';
if ($this->generate_fields) {
    // Start output buffering
    ob_start();

    // Generate the button HTML
    $this->generate_populate_fields_script();

    // Generate the hidden field HTML
    $hidden_field_html = $this->generate_hidden_data_field();

    // Combine both HTML outputs
    $combined_html = ob_get_contents() . $hidden_field_html;
    ob_end_clean();

    // Create a new instance of the table specifically for the button
    $button_table = new Duzz_Admin_Table_Form_Factory('table', 'generate_button');

    // Add the combined HTML directly as a row to the button table
    $button_table->duzz_addRow(['Generate ACF Keys', $combined_html]);

    // Render the button table to get its HTML
    $button_table_html = $button_table->duzz_render();
}

// Concatenate the button table HTML with the fields table HTML
return $button_table_html . $fields_table_html;


}


private function generate_hidden_data_field() {
    $fieldData = $this->field_data;

    // Generate random keys for each field
    foreach ($fieldData as $key => $value) {
        $fieldData[$key] = 'field_' . bin2hex(random_bytes(8));
    }

    $encodedDefaultValues = htmlspecialchars(json_encode($fieldData), ENT_QUOTES, 'UTF-8');

    $hiddenFieldObj = new HTMLNamespace\Duzz_Field('input', [
        'type' => 'hidden',
        'id' => 'default-values-data',
        'value' => $encodedDefaultValues
    ]);

    ob_start();
    $hiddenFieldObj->duzz_render();
    return ob_get_clean();
}




private function generate_populate_fields_script() {
    $defaultValues = json_encode($this->field_data);

    // Ensure that $defaultValues is correctly encoded
    $encodedDefaultValues = htmlspecialchars($defaultValues, ENT_QUOTES, 'UTF-8');

    $buttonObj = new HTMLNamespace\Duzz_Field('input', [
        'type' => 'button',
        'class' => 'button-primary',
        'id' => 'generate-fields-button',
        'value' => 'Generate',
        'data-default-values' => $encodedDefaultValues
    ]);

    ob_start();
    $buttonObj->duzz_render();
}



public function duzz_forms_sanitize_field_data_callback( $input ) {
    $output = $this->sanitize_field_data( $input, $this->field_data );

    return $output;
}


public function sanitize_field_data( $input, $default_field_data ) {
    // Sanitize and validate the field numbers
    $output = array();
    foreach ( $default_field_data as $key => $default_value ) {
        if ( isset( $input[$key] ) && $input[$key] !== '' ) {
            if ( is_int( $default_value ) ) {
                // If the default value is an integer, sanitize and set the value as an integer
                $output[$key] = absint( $input[$key] );
            } else {
                // If the default value is not an integer, use the input value as is
                $output[$key] = $input[$key];
            }
        } else {
            // If the value is empty, set the default value
            $output[$key] = $default_value;
        }
    }
    return $output;
}




    public static function duzz_get_duzz_settings_field_data(string $option_name) {
        return get_option($this->duzz_get_option_name(), []);
    }

      public function duzz_get_duzz_connector_field_data() {
        return get_option( $this->duzz_get_option_name(), [] );
    }

      public function duzz_field_callback( $args ) {
    return 'duzz_forms_' . $this->page_name . '_connector_callback';
    }

   public function duzz_get_option_name() {
        // Generate the option name based on the form ID
        return $this->page_name . '_' . $this->section_name . '_field_data';
    }


   public function duzz_get_section_id() {
        // Generate the option name based on the form ID
        return 'duzz_forms_' . $this->section_name . '_connector_section';
    }

}
