<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_Toggle {
    protected $tag;
    protected $attributes;
    protected $toggle_value;

    public function __construct($attributes = array(), $toggle_value = false) {
        $this->tag = 'input';
        $this->attributes = $attributes;
        $this->toggle_value = $toggle_value;
    }

    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }

    public function render() {
        echo '<label class="toggle-switch">'; 
        echo '<' . esc_html($this->tag) . $this->getAttributesString() . '>';
        echo '<span class="slider"></span> ' . esc_html__('Testing: ') . ($this->toggle_value ? esc_html__('On') : esc_html__('Off'));
        echo '</label>';
    }

    protected function getAttributesString() {
        $attributesString = '';

        foreach ($this->attributes as $name => $value) {
            $attributesString .= ' ' . esc_html($name) . '="' . esc_attr($value) . '"';
        }

        // Add the type attribute for input elements.
        if ($this->tag === 'input' && !isset($this->attributes['type'])) {
            $attributesString .= ' type="checkbox"';
        }

        // Add the checked attribute if the toggle_value is true.
        if ($this->toggle_value) {
            $attributesString .= ' checked="checked"';
        }

        return $attributesString;
    }
}
