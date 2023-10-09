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
                $nonceName = $key === 'action' ? '_wpnonce' : $nonceKey . '-pass-post-nonce';
                $nonceAction = $key === 'action' ? str_replace('-', '_', $nonceKey) . '_action_pass_post_nonce' : str_replace('-', '_', $nonceKey) . '_action_pass_post_nonce';

                  if ($action !== 'action' && $action !== 'nonced' && !is_null($key) && (!isset($_POST[$nonceName]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonceName])), $nonceAction))) {
                return null;
            }

                if (isset($_POST[$key])) {
                    if (is_array($_POST[$key])) {
                        return array_map(function($item) {
                            return esc_html(sanitize_text_field($item));
                        }, $_POST[$key]);
                    } else {
                        return esc_html(sanitize_text_field($_POST[$key]));
                    }
                } else {
                    return null;
                }

            case 'GET':
                $nonceName = $nonceKey . '-pass-get-nonce';
                $nonceAction = str_replace('-', '_', $nonceKey) . '_pass_get_nonce';

                if ($action !== 'off' && !is_null($key) && (!isset($_GET[$nonceName]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_GET[$nonceName])), $nonceAction))) {
                    return null;
                }

                if (isset($_GET[$key])) {
                    if (is_array($_GET[$key])) {
                        return array_map(function($item) {
                            return esc_html(sanitize_text_field($item));
                        }, $_GET[$key]);
                    } else {
                        return esc_html(sanitize_text_field($_GET[$key]));
                    }
                } else {
                    return null;
                }

            case 'REQUEST':
                $nonceName = $nonceKey . '-pass-request-nonce';
                $nonceAction = str_replace('-', '_', $nonceKey) . '_pass_request_nonce';

                if ($action !== 'off' && !is_null($key) && (!isset($_REQUEST[$nonceName]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST[$nonceName])), $nonceAction))) {
                    return null;
                }

                if (isset($_REQUEST[$key])) {
                    if (is_array($_REQUEST[$key])) {
                        return array_map(function($item) {
                            return esc_html(sanitize_text_field($item));
                        }, $_REQUEST[$key]);
                    } else {
                        return esc_html(sanitize_text_field($_REQUEST[$key]));
                    }
                } else {
                    return null;
                }

            default:
                return null;
        }
    }
}
