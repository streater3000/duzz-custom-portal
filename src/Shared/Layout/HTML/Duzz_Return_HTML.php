<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_Return_HTML {
    protected $tag;
    protected $attributes;
    protected $children = array();

    // List of self-closing tags
    protected static $selfClosingTags = array(
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 
        'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'
    );

    public function __construct($tag, $attributes = array()) {
        $this->tag = $tag;
        $this->attributes = $attributes;
    }

    public function duzz_setAttribute($key, $value) {
        if(is_array($value)){
            $value = '';  // set a default value or use implode() to convert the array into a string
        }
        $this->attributes[$key] = $value;
    }

public function duzz_addChild($tagOrObject, $attributes = array(), $content = '') {
    if ($tagOrObject instanceof Duzz_Return_HTML) {
        // Directly add the object if it's an instance of Duzz_Return_HTML
        $this->children[] = $tagOrObject;
        return $tagOrObject; // Return the passed object
    } else {
        $child = new Duzz_Return_HTML($tagOrObject, $attributes);
        $child->duzz_setContent($content);
        $this->children[] = $child;
        return $child;  // Return the new child instance
    }
}


    public function duzz_setContent($content) {
        $this->children = array($content);  // Overrides existing children with content
    }

    public function duzz_render() {
    $attributesString = '';
    foreach ($this->attributes as $name => $value) {
        if (is_array($value)) {
            $value = '';
        }
        $attributesString .= ' ' . $name . '="' . esc_attr($value) . '"';
    }

    // If the tag name is empty or undefined, just return the children's content
    if (empty($this->tag)) {
        $html = '';
        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'render')) {
                $html .= $child->duzz_render();
            } else {
                $html .= $child;
            }
        }
        return $html;
    }

    // If it's a self-closing tag
    if (in_array($this->tag, self::$selfClosingTags)) {
        return '<' . $this->tag . $attributesString . ' />';
    }

    $html = '<' . $this->tag . $attributesString . '>';

    foreach ($this->children as $child) {
        if (is_object($child) && method_exists($child, 'render')) {
            $html .= $child->duzz_render();
        } else {
            $html .= $child;
        }
    }

    $html .= '</' . $this->tag . '>';

    return $html;
}


    public function __toString() {
        return $this->duzz_render();
    }
}
