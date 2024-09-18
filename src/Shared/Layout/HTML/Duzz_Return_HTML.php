<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_Return_HTML {
    protected $tag;
    protected $attributes;
    protected $children = array();

    protected static $selfClosingTags = array(
        'area', 'base', 'br', 'col', 'command', 'embed', 'hr', 
        'img', 'input', 'keygen', 'link', 'meta', 'param', 'source', 'track', 'wbr'
    );

    public function __construct($tag, $attributes = array()) {
        $this->tag = $tag;
        $this->attributes = $attributes;
    }

    public function duzz_setAttribute($key, $value) {
        if (is_array($value)) {
            $value = '';  // set a default value or use implode() to convert the array into a string
        }
        $this->attributes[$key] = $value;
    }

    public function duzz_addChild($tagOrObject, $attributes = array(), $content = '') {
        if ($tagOrObject instanceof Duzz_Return_HTML) {
            $this->children[] = $tagOrObject;
            return $tagOrObject;
        } else {
            $child = new Duzz_Return_HTML($tagOrObject, $attributes);
            $child->duzz_setContent($content);
            $this->children[] = $child;
            return $child;
        }
    }

    public function duzz_setContent($content) {
        $this->children = array($content);
    }

    public function duzz_render() {
        // Create hook before applying filters
        do_action('duzz_render_before', $this);

        $content = $this->duzz_getContent();

        // Apply filter based on the first class name
        $filter_name = $this->getFilterName();
        $content = apply_filters($filter_name, $content);

        return $content;
    }

    protected function duzz_getContent() {
        $attributesString = '';
        foreach ($this->attributes as $name => $value) {
            if (is_array($value)) {
                $value = '';
            }
            $attributesString .= ' ' . $name . '="' . esc_attr($value) . '"';
        }

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

    protected function getFilterName() {
        $class = isset($this->attributes['class']) ? $this->attributes['class'] : '';
        $class_names = explode(' ', $class);
        $first_class = reset($class_names);

        return 'duzz_filter_' . str_replace('-', '_', $first_class);
    }

    public function __toString() {
        return $this->duzz_render();
    }
}

