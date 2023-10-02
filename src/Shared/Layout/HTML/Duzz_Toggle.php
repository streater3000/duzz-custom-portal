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
        
        echo '<', esc_html($this->tag); // echo opening tag
        // Loop through each attribute and echo them individually
        foreach ($this->attributes as $name => $value) {
            echo ' ', esc_html($name), '="', esc_attr($value), '"';
        }
        
        // Additional attributes as per logic
        if ($this->tag === 'input' && !isset($this->attributes['type'])) {
            echo ' type="checkbox"';
        }
        if ($this->toggle_value) {
            echo ' checked="checked"';
        }

        // Close the tag
        echo '>';
        
        // You can use wp_kses_post here if you are sure that $toggleText will never have anything but post allowed tags.
        $toggleText = $this->toggle_value ? wp_kses_post(__('On')) : wp_kses_post(__('Off'));
        echo '<span class="slider"></span> ', esc_html($toggleText);
        
        echo '</label>'; // close the label tag
    }
}
