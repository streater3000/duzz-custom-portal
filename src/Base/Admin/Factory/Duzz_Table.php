<?php

namespace Duzz\Base\Admin\Factory;

class Duzz_Table{
    protected $tag;
    protected $attributes;
    protected $children;

    public function __construct($tag, $attributes = array(), $children = array()) {
        $this->tag = $tag;
        $this->attributes = $attributes;
        $this->children = $children;
    }

    public function duzz_setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function duzz_addChild($child) {
        $this->children[] = $child;
    }

    public function duzz_render() {
        $attributesString = '';

        foreach ($this->attributes as $name => $value) {
            $attributesString .= ' ' . $name . '="' . esc_attr($value) . '"';
        }

        $html = '<' . $this->tag . $attributesString . '>';

        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'duzz_render')) {
                $html .= $child->duzz_render();
            } else {
                $html .= (string) $child;
            }
        }

        $html .= '</' . $this->tag . '>';

        return $html;
    }
}