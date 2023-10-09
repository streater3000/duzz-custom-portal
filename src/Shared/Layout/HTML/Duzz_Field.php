<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_Field {
    protected $tag;
    protected $attributes = [];
    protected $children = [];
    protected $selfClosingTags = ['input', 'img', 'br', 'hr', 'meta', 'link'];
    protected $allowedTags = ['input', 'textarea', 'select', 'button', 'label', 'fieldset', 'legend', 'output', 'datalist', 'optgroup', 'option'];

    public function __construct($tag, $attributes = [], $children = []) {
        $this->tag = $this->duzz_sanitizeTag($tag);
        $this->attributes = $attributes;
        $this->children = is_array($children) ? $children : [$children];
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
        $this->children[] = $child;
    }

    public function duzz_render() {
        $tag = esc_html($this->tag); // Escape once and reuse
        if (in_array($tag, $this->selfClosingTags)) {
            echo '<', esc_html($this->tag);
            foreach ($this->attributes as $name => $value) {
                echo ' ', esc_html($name), '="', esc_attr($value), '"';
            }
            echo ' />';
        } else {
            $innerContent = $this->duzz_getInnerContent();
            echo '<', esc_html($this->tag);
            foreach ($this->attributes as $name => $value) {
                echo ' ', esc_html($name), '="', esc_attr($value), '"';
            }
            // Assuming $innerContent is meant to be HTML, use wp_kses_post() or another suitable function to sanitize
            echo '>', wp_kses_post($innerContent), '</', esc_html($this->tag), '>';
        }
    }

    protected function duzz_getInnerContent() {
        $content = '';
        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'duzz_render')) {
                $content .= $child->render(); // Ensure this render method is also escaping its output properly.
            } else {
                $content .= esc_html((string)$child);
            }
        }
        return $content;
    }
}
