<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_Table {
    protected $tag;
    protected $attributes;
    protected $children = array();
    protected $allowedTags = ['table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th', 'caption', 'colgroup', 'col'];
    protected $selfClosingTags = [];

    public function __construct($tag, $attributes = array()) {
        $this->tag = $this->duzz_sanitizeTag($tag);
        $this->attributes = $attributes;
    }

    protected function duzz_sanitizeTag($tag) {
        // Check if the tag is in the allowed tags list
        if (in_array($tag, $this->allowedTags)) {
            return $tag;
        }
        // Default to 'div' if not allowed
        return 'div';
    }

    public function duzz_setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function duzz_addChild($child) {
        if (is_object($child) && method_exists($child, 'duzz_render')) {
            // If the child is a Duzz_Table instance, store it directly
            $this->children[] = $child;
        } else {
            // Otherwise, store the child content as a string
            $this->children[] = (string) $child;
        }
    }


protected function duzz_getAllowedHtml() {
    return array(
            'input' => array(
                'name'    => array(),
                'value'   => array(),
                'id'      => array(),
                'type'    => array(),
                'checked' => array(),
                'class'   => array(),
            ),
            'label' => array(
                'class' => array(),
            ),
            'span' => array(
                'class' => array(),
            ),
            'select' => array(
                'name'               => array(),
                'id'                 => array(),
                'style'              => array(),
                'class'              => array(),
                'data-max-selections' => array(),
                'multiple'           => array(),
            ),
            'option' => array(
                'value'    => array(),
                'selected' => array(),
            ),
            'textarea' => array(
                    'name'  => array(),
                    'class' => array(),
                    'id'    => array(),
                    'rows'  => array(),
                    'cols'  => array(),
                ),
            'toggle' => array(
                    'data-toggle-id' => array(),
                    'class' => array(),
                ),
    );
}

public function duzz_render() {
    $allowed_html = $this->duzz_getAllowedHtml(); 
    
    if (!in_array($this->tag, $this->selfClosingTags)) {
        echo '<', esc_html($this->tag);
        
        foreach ($this->attributes as $name => $value) {
            echo ' ', esc_html($name), '="', esc_attr($value), '"';
        }
        
        echo '>';
        
        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'duzz_render')) {
                $child->duzz_render(); // Assuming that the render() method is properly escaping the output.
            } else {
                echo wp_kses($child, $allowed_html);
            }
        }
        
        echo '</', esc_html($this->tag), '>';
    } else {
        echo '<', esc_html($this->tag);
        
        foreach ($this->attributes as $name => $value) {
            echo ' ', esc_html($name), '="', esc_attr($value), '"';
        }
        
        echo ' />';
    }
}

}
