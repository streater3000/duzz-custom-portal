<?php

namespace Duzz\Core;

class Duzz_Data_Passer {

    public function duzz_verify_nonce($nonce_action) {
        $nonce = $this->duzz_retrieve('POST', '_wpnonce', 'action');
        if (!$nonce || !wp_verify_nonce($nonce, $nonce_action)) {
            wp_die('Nonce verification failed!', 403);
        }
    }

    public function duzz_retrieve($method, $key = null, $action = null) {
        if (!in_array(strtoupper($method), ['POST', 'GET', 'REQUEST']) || empty($key)) {
            return null;
        }

        $nonceKey = str_replace('_', '-', $key);
        $method = strtoupper($method);

        switch ($method) {
            case 'POST':
                return $this->get_from_post($key, $nonceKey, $action);
            case 'GET':
                return $this->get_from_get($key, $nonceKey, $action);
            case 'REQUEST':
                return $this->get_from_request($key, $nonceKey, $action);
            default:
                return null;
        }
    }

    private function get_from_post($key, $nonceKey, $action) {
        $nonceName = $key === 'action' ? '_wpnonce' : $nonceKey . '-pass-post-nonce';
        $nonceAction = $key === 'action' ? str_replace('-', '_', $nonceKey) . '_action_pass_post_nonce' : str_replace('-', '_', $nonceKey) . '_action_pass_post_nonce';

        if ($action !== 'action' && $action !== 'nonced' && (!isset($_POST[$nonceName]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonceName])), $nonceAction))) {
            return null;
        }

        if (!isset($_POST[$key])) {
            return null;
        }

        if (is_array($_POST[$key])) {
            $sanitized = [];
            foreach ($_POST[$key] as $index => $item) {
                $sanitized[] = esc_html(sanitize_text_field($_POST[$key][$index]));
            }
            return $sanitized;
        }

        return esc_html(sanitize_text_field($_POST[$key]));
    }

    private function get_from_get($key, $nonceKey, $action) {
        $nonceName = $nonceKey . '-pass-get-nonce';
        $nonceAction = str_replace('-', '_', $nonceKey) . '_pass_get_nonce';

        if ($action !== 'off' && !is_null($key) && (!isset($_GET[$nonceName]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET[$nonceName])), $nonceAction))) {
            return null;
        }

        if (!isset($_GET[$key])) {
            return null;
        }

        if (is_array($_GET[$key])) {
            $sanitized = [];
            foreach ($_GET[$key] as $index => $item) {
                $sanitized[] = esc_html(sanitize_text_field($_GET[$key][$index]));
            }
            return $sanitized;
        }

        return esc_html(sanitize_text_field($_GET[$key]));
    }

    private function get_from_request($key, $nonceKey, $action) {
        $nonceName = $nonceKey . '-pass-request-nonce';
        $nonceAction = str_replace('-', '_', $nonceKey) . '_pass_request_nonce';

        if ($action !== 'off' && (!isset($_REQUEST[$nonceName]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST[$nonceName])), $nonceAction))) {
            return null;
        }

        if (!isset($_REQUEST[$key])) {
            return null;
        }

        if (is_array($_REQUEST[$key])) {
            $sanitized = [];
            foreach ($_REQUEST[$key] as $index => $item) {
                $sanitized[] = esc_html(sanitize_text_field($_REQUEST[$key][$index]));
            }
            return $sanitized;
        }

        return esc_html(sanitize_text_field($_REQUEST[$key]));
    }
}
