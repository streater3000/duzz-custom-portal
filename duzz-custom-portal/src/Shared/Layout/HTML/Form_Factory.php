<?php

namespace Duzz\Shared\Layout\HTML;

class Form_Factory {
    protected $tag;
    protected $attributes;
    protected $children = array();

    public function __construct($tag, $attributes = array()) {
        $this->tag = $tag;
        $this->attributes = $attributes;
    }

public function setAttribute($key, $value) {
    if(is_array($value)){
        $value = '';  // set a default value or use implode() to convert the array into a string
    }
    $this->attributes[$key] = $value;
}


    public function addChild($tag, $attributes = array(), $content = '') {
        $child = new Form_Factory($tag, $attributes);
        $child->setContent($content);
        $this->children[] = $child;
        return $child;
    }

    public function setContent($content) {
        $this->children = array($content);
    }

    public function render() {
        $attributesString = '';

foreach ($this->attributes as $name => $value) {
    if (is_array($value)) {
        $value = ''; // or handle it in another appropriate way
    }
    $attributesString .= ' ' . $name . '="' . esc_attr($value) . '"';
}



        $html = '<' . $this->tag . $attributesString . '>';

        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'render')) {
                $html .= $child->render();
            } else {
                $html .= $child;
            }
        }

        $html .= '</' . $this->tag . '>';

        return $html;
    }
}
