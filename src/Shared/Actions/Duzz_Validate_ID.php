<?php

namespace Duzz\Shared\Actions;

class Duzz_Validate_ID {

public static function duzz_validate($id) {
        // Convert id to string for easier handling
        $id_str = (string) $id;

        // Ensure id is a 16-digit number
        if (strlen($id_str) !== 16 || !is_numeric($id)) {
            return false;
        }



        return true;
    }
}
