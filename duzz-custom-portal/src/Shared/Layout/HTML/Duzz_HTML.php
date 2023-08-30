<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_HTML {
    protected $tag;
    protected $attributes = [];
    protected $children = [];
    protected $selfClosingTags = ['br', 'embed', 'img', 'link', 'param', 'source', 'track', 'wbr'];
    protected $allowedTags = [
        'a', 'article', 'aside', 'b', 'blockquote', 'body', 'br', 'button', 'canvas', 'caption',
        'cite', 'code', 'col', 'colgroup', 'data', 'datalist', 'dd', 'del', 'details',
        'dfn', 'dialog', 'div', 'dl', 'dt', 'em', 'embed', 'fieldset', 'figcaption',
        'figure', 'footer', 'form', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'head',
        'header', 'hr', 'html', 'i', 'img', 'ins', 'kbd', 'label', 'legend',
        'li', 'link', 'main', 'map', 'mark', 'menu', 'meter', 'nav',
        'ol', 'optgroup', 'option', 'output', 'p', 'param', 'picture', 'pre',
        'progress', 'q', 'rb', 'rp', 'rt', 'rtc', 'ruby', 's', 'samp', 'section',
        'small', 'source', 'span', 'strong', 'style', 'sub', 'summary', 'sup', 'table',
        'tbody', 'td', 'template', 'tfoot', 'th', 'thead', 'time', 'title', 'tr', 'track',
        'u', 'ul', 'var', 'video', 'wbr'
    ];

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
        echo '<', esc_html($this->tag), $attributesString, ' />';
    } else {
        echo '<', esc_html($this->tag), $attributesString, '>';

        // Capture and echo inner content
        ob_start();
        echo $this->getInnerContent();
        ob_end_flush();

        echo '</', esc_html($this->tag), '>';
    }
}


    protected function getAttributesString() {
        $attributesString = '';
        foreach ($this->attributes as $name => $value) {
            if ($this->tag === 'a' && $name === 'href') {
                $attributesString .= ' ' . esc_html($name) . '="' . esc_url($value) . '"';
            } else {
                $attributesString .= ' ' . esc_html($name) . '="' . esc_attr($value) . '"';
            }
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
