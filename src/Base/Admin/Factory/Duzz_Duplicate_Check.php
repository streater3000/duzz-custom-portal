<?php

namespace Duzz\Base\Admin\Factory;


class Duzz_Duplicate_Check {
    public static function has_duplicate_value($value, $array) {
        $count = 0;
        if (is_array($array)) {
            foreach ($array as $array_value) {
                if ($value == $array_value) {
                    $count++;
                }
            }
        }
        return $count > 1;
    }
}
