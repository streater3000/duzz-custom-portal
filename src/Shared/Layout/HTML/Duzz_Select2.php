<?php

namespace Duzz\Shared\Layout\HTML;

use Duzz\Shared\Layout\Script\Duzz_Select2_Script;

class Duzz_Select2 {
    protected $tag;
    protected $attributes;
    protected $available_selections;
    protected $selected_columns;

    public function __construct($attributes = array(), $available_selections = array(), $selected_columns = array()) {
        $this->tag = 'select';
        $this->attributes = $attributes;
        $this->available_selections = $available_selections;
        $this->selected_columns = $selected_columns;
        Duzz_Select2_Script::duzz_select2_used();
    }

    public function duzz_setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function duzz_render() {
        echo '<', esc_html($this->tag); // Opening tag with escaped tag name
        // Loop through each attribute and echo them individually with proper escaping
        foreach ($this->attributes as $name => $value) {
            echo ' ', esc_html($name), '="', esc_attr($value), '"';
        }
        echo '>'; // Close the opening tag
        $this->duzz_availableSelections(); // Call to the method for available selections
        echo '</', esc_html($this->tag), '>'; // Close the tag with escaped name
    }

    protected function duzz_availableSelections() {
        foreach ($this->available_selections as $field_name => $default_value) {
            echo '<option value="', esc_attr($field_name), '"';
            if (in_array($field_name, $this->selected_columns)) {
                echo ' selected="selected"';
            }
            echo '>', esc_html($field_name), '</option>';
        }
    }
}

