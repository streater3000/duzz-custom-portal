<?php

namespace Duzz\Shared\Actions;

class Duzz_IP_Check{

    function duzz_get_client_ip_address() {
        $ip = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // To check IP from share internet
            $ip = sanitize_text_field($_SERVER['HTTP_CLIENT_IP']);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // To check IP is from proxy
            $ip = sanitize_text_field($_SERVER['HTTP_X_FORWARDED_FOR']);
        } else {
            $ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
        }
        
        // Validate IP address
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        } else {
            // Return a default IP or false if the IP address is not valid.
            return '0.0.0.0';
        }
    }
}
