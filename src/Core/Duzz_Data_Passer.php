<?php

namespace Duzz\Core;

class Duzz_Data_Passer {

    public function verify_nonce($nonce_action) {
        $nonce = $this->retrieve('POST', '_wpnonce', 'action');
        if (!$nonce || !wp_verify_nonce($nonce, $nonce_action)) {
            wp_die('Nonce verification failed!', 403);
        }
    }

    public function retrieve($method, $key = null, $action = null) {

        if (!in_array(strtoupper($method), ['POST', 'GET', 'REQUEST']) || empty($key)) {
            return null;
        }

        $nonceKey = str_replace('_', '-', $key);
        $method = strtoupper($method);
        $request = null;

        switch ($method) {
            case 'POST':
                $nonceName = $key === 'action' ? '_wpnonce' : $nonceKey . '-pass-post-nonce';
                $nonceAction = $key === 'action' ? str_replace('-', '_', $nonceKey) . '-action_pass_post_nonce' : str_replace('-', '_', $nonceKey) . '_pass_post_nonce';

                // If $action is not 'action' and $key is not null then perform the nonce verification.
                if ($action !== 'action' && !is_null($key)) {
                    if (!isset($_POST[$nonceName]) || !wp_verify_nonce($_POST[$nonceName], $nonceAction)) {
                        return null;
                    }
                }
                
                $request = $_POST;

                if (is_null($key)) {
                    array_walk_recursive($request, function (&$item) {
                        $item = sanitize_text_field($item);
                    });
                }

                break;

            case 'GET':
                $nonceName = $nonceKey . '-pass-get-nonce';
                $nonceAction = str_replace('-', '_', $nonceKey) . '_pass_get_nonce';

                if ($action !== 'off' && (!isset($_GET[$nonceName]) || !wp_verify_nonce($_GET[$nonceName], $nonceAction))) {
                    return null;
                }
                $request = $_GET;
                break;

            case 'REQUEST':
                $nonceName = $nonceKey . '-pass-request-nonce';
                $nonceAction = str_replace('-', '_', $nonceKey) . '_pass_request_nonce';

                if ($action !== 'off' && (!isset($_REQUEST[$nonceName]) || !wp_verify_nonce($_REQUEST[$nonceName], $nonceAction))) {

                    return null;
                }
                $request = $_REQUEST;
                break;
            default:
                return null;
        }

        return is_null($key) ? $request : ($request[$key] ?? null);
    }
}


