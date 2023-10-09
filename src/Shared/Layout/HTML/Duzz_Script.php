<?php

namespace Duzz\Shared\Layout\HTML;

class Duzz_Script {
    private $content;
    private $allowHtml;

    public function __construct($content, $allowHtml = false) {
        $this->content = $content;
        $this->allowHtml = $allowHtml;
    }

    public function duzz_render() {
        if ($this->allowHtml) {
            // If HTML content is allowed, expand the allowed_html list
            $allowed_html = array(
                'script' => array(),
                'span' => array(
                    'class' => array(),
                ),
                // Add more HTML tags and their attributes as needed
            );
        } else {
            // Only allow the script tag if no HTML content is expected
            $allowed_html = array(
                'script' => array()
            );
        }

        echo wp_kses('<script>' . $this->content . '</script>', $allowed_html);
    }
}