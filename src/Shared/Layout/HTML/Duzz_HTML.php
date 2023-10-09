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
    $this->tag = $this->duzz_sanitizeTag($tag);
    $this->attributes = $attributes;
    $this->children = is_array($children) ? $children : [$children];
}

    protected function duzz_sanitizeTag($tag) {
        if (in_array($tag, $this->allowedTags)) {
            return $tag;
        }
        return 'div'; // Default to 'div' if not allowed
    }

    public function duzz_setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function duzz_addChild($child) {
        $this->children[] = $child;
    }


public function duzz_render() {

    $allowed_html = $this->duzz_getAllowedHtml(); 

    if (in_array($this->tag, $this->selfClosingTags)) {
        echo '<', esc_html($this->tag);
            foreach ($this->attributes as $name => $value) {
                           echo ' ', esc_html($name), '="', esc_attr($value), '"';
                       }
        echo ' />';
    } else {
 
       echo '<', esc_html($this->tag);
            foreach ($this->attributes as $name => $value) {
                echo ' ', esc_html($name), '="', esc_attr($value), '"';
            }
        echo '>';
        echo wp_kses($this->duzz_getInnerContent(), $allowed_html);
        echo '</', esc_html($this->tag), '>';
    }
}

    protected function duzz_getAllowedHtml() {
        $allowed_html = array();
        foreach ($this->allowedTags as $tag) {
            $allowed_html[$tag] = array(
                'class' => true,
                'id' => true,
                'style' => true,
            );
        }
        return $allowed_html;
    }


    protected function duzz_getInnerContent() {
        $content = '';
        foreach ($this->children as $child) {
            if (is_object($child) && method_exists($child, 'duzz_render')) {
                $content .= $child->duzz_render(); // the render method should handle its own escaping
            } else {
                $content .= esc_html((string) $child);
            }
        }
        return $content;
    }
}
