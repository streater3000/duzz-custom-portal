<?php

namespace Duzz\Shared\Actions;

class Duzz_Sanitize {

    /**
     * Custom sanitization function to allow specific HTML tags.
     *
     * @param string $content The content to sanitize.
     * @return string The sanitized content.
     */
    public static function custom_kses($content) {
        $allowed_html = array(
            'div' => array(
                'class' => array(),
            ),
            'table' => array(),
            'tbody' => array(),
            'tr' => array(
                'class' => array(),
            ),
            'td' => array(
                'class' => array(),
            ),
            'br' => array(),
            //... add other tags and attributes you need
        );

        return wp_kses($content, $allowed_html);
    }
}
