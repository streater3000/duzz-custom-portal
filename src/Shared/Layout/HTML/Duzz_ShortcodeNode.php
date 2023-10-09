<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_ShortcodeNode {
    protected $tag = 'div'; // default to 'div' as per original Element
    protected $attributes;
    protected $children = [];
    private $shortcode;

    public function __construct($shortcode, $attributes = array(), $children = array()) {
        $this->attributes = $attributes;
        $this->children = is_array($children) ? $children : array($children);
        $this->shortcode = $shortcode;
    }

    public function duzz_setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function duzz_addChild($child, $identifier = null) {
        // Call the action hook if it exists
        if ($identifier !== null && has_action("element_child_hook_{$identifier}")) {
            do_action("element_child_hook_{$identifier}", $child);
        }

        // Apply the filter if it exists
        if ($identifier !== null && has_filter("element_child_filter_{$identifier}")) {
            $child = apply_filters("element_child_filter_{$identifier}", $child, $identifier);
        }

        // Add the child object to the children array
        $this->children[] = $child;
    }

    public function duzz_render() {
        // Assuming the shortcode is implemented by the plugin, you would call the WordPress do_shortcode() function to render the shortcode content.
        $shortcode_content = do_shortcode($this->shortcode);
        
        // Add the shortcode content as a child of the div element
        $this->children[] = $shortcode_content;

        $attributes_string = '';
        foreach ($this->attributes as $key => $value) {
            $attributes_string .= ' ' . $key . '="' . $value . '"';
        }

        $children_string = '';
        foreach ($this->children as $child) {
            $children_string .= is_object($child) && method_exists($child, 'render') ? $child->render() : (string) $child;
        }

        return "<{$this->tag}{$attributes_string}>{$children_string}</{$this->tag}>";
    }

    public function __toString() {
        return $this->duzz_render();
    }
}
