<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_Table {
    protected $tag;
    protected $attributes;
    protected $children = array();
    protected $allowedTags = ['table', 'thead', 'tbody', 'tfoot', 'tr', 'td', 'th', 'caption', 'colgroup', 'col'];

    public function __construct($tag, $attributes = array()) {
        $this->tag = $this->sanitizeTag($tag);
        $this->attributes = $attributes;
    }

    protected function sanitizeTag($tag) {
        // Check if the tag is in the allowed tags list
        if (in_array($tag, $this->allowedTags)) {
            return $tag;
        }
        // Default to 'div' if not allowed
        return 'div';
    }

    public function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function addChild($child) {
        if (is_object($child) && method_exists($child, 'render')) {
            // If the child is a Duzz_Table instance, store it directly
            $this->children[] = $child;
        } else {
            // Otherwise, store the child content as a string
            $this->children[] = (string) $child;
        }
    }

    public function render() {
        $attributesString = '';

        foreach ($this->attributes as $name => $value) {
            $attributesString .= ' ' . $name . '="' . esc_attr($value) . '"';
        }

        echo '<', esc_html($this->tag), $attributesString, '>';

        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'render')) {
                // Directly render the child if it's a Duzz_Table instance
                $child->render();
            } else {
                // Otherwise, echo the child content (assuming the content is already escaped, else consider using esc_html)
                echo $child;
            }
        }

        echo '</', esc_html($this->tag), '>';
    }
}
