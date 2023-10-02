<?php

namespace Duzz\Shared\Actions;

use Duzz\Core\Duzz_Data_Passer;

class Duzz_Error {

    protected static $data_passer;

    public static function initialize() {
        self::$data_passer = new Duzz_Data_Passer();
    }

    public static function print_error_message($key, $message, $is_success = true) {
        // Use Duzz_Data_Passer to retrieve GET variable
        $value = self::$data_passer->retrieve('GET', $key);
        
        if (($value === 'true') === $is_success) {
            return '<p class="' . ($is_success ? 'duzz-success' : 'duzz-error') . '">' . $message . '</p>';
        }
        return '';
    }

    public static function list_error_messages() {
        $message = '';
        $message .= self::print_error_message('noprojectemail', 'Error: Email does not exist. Try again.');
        $message .= self::print_error_message('resendprojectemail', 'Email resent with Project Link.');
        $message .= self::print_error_message('failedprojectemail', 'Try again or contact us for help.');
        $message .= self::print_error_message('sentinvite', 'Customer invite sent.');
        
        return $message;
    }
}

Duzz_Error::initialize();

