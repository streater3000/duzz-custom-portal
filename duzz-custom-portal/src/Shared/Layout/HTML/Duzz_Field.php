<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_Field {
    protected $tag;
    protected $attributes = [];
    protected $children = [];
    protected $selfClosingTags = ['input', 'img', 'br', 'hr', 'meta', 'link'];
    protected $allowedTags = ['input', 'textarea', 'select', 'button', 'label', 'fieldset', 'legend', 'output', 'datalist', 'optgroup', 'option'];

public function __construct($tag, $attributes = [], $children = []) {
    $this->tag = $this->sanitizeTag($tag);
    $this->attributes = $attributes;
    $this->children = is_array($children) ? $children : [$children];
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
        $this->children[] = $child;
    }

    public function render() {
        $attributesString = $this->getAttributesString();

        if (in_array($this->tag, $this->selfClosingTags)) {
            echo '<', $this->tag, $attributesString, ' />';
        } else {
            $innerContent = $this->getInnerContent();
            echo '<', $this->tag, $attributesString, '>', $innerContent, '</', $this->tag, '>';
        }
    }

    protected function getAttributesString() {
        $attributesString = '';
        foreach ($this->attributes as $name => $value) {
            $attributesString .= ' ' . $name . '="' . esc_attr($value) . '"';
        }
        return $attributesString;
    }

    protected function getInnerContent() {
        $content = '';
        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'render')) {
                $content .= $child->render(); // the render method should handle its own escaping
            } else {
                $content .= esc_html((string) $child);
            }
        }
        return $content;
    }
}
