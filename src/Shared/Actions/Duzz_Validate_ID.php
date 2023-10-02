<?php

namespace Duzz\Shared\Actions;

class Duzz_Validate_ID {

public static function validate($id) {
        // Convert id to string for easier handling
        $id_str = (string) $id;

        // Ensure id is a 16-digit number
        if (strlen($id_str) !== 16 || !is_numeric($id)) {
            return false;
        }

        // Extract year, day, and month
        $id_year  = intval(substr($id_str, 4, 2));  // 5th and 6th digits
        $id_day   = intval(substr($id_str, 6, 2));  // 7th and 8th digits
        $id_month = intval(substr($id_str, 8, 2));  // 9th and 10th digits

        $current_year = intval(gmdate('y'));  // Get current year in 'yy' format

        // Validate year
        if ($id_year < 22 || $id_year > $current_year) {
            return false;
        }

        // Validate day
        if ($id_day < 1 || $id_day > 31) {
            return false;
        }

        // Validate month
        if ($id_month < 1 || $id_month > 12) {
            return false;
        }

        return true;
    }
}
