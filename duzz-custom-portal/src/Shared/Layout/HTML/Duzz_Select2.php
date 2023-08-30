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

        Duzz_Select2_Script::select2_used();
    }

    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function render() {
        echo '<' . $this->tag . $this->getAttributesString() . '>';
        $this->availableSelections();
        echo '</' . $this->tag . '>';
    }

    protected function getAttributesString() {
        $attributesString = '';

        foreach ($this->attributes as $name => $value) {
            $attributesString .= ' ' . $name . '="' . esc_attr($value) . '"';
        }

        return $attributesString;
    }

    protected function availableSelections() {
        foreach ($this->available_selections as $field_name => $default_value) {
            $selected = in_array($field_name, $this->selected_columns) ? ' selected="selected"' : '';
            echo '<option value="' . esc_attr($field_name) . '"' . $selected . '>';
            echo esc_html($field_name);
            echo '</option>';
        }
    } 
}
