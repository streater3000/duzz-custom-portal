<?php

namespace Duzz\Shared\Actions;

class Duzz_Project_Number {
    public static function generate() {
        $yeardate = gmdate("y");
        $monthdate = gmdate("m");
        $daydate = gmdate("d");
        $datehours = gmdate("h");
        $dateminutes = gmdate("i");
        $dateseconds = gmdate("s");
        $zerodate = 0;
        $dmydate = $yeardate . $daydate . $monthdate . $datehours . $dateminutes . $dateseconds;
        $ran = wp_rand(999, 9999); // replaced rand() with wp_rand()
        $dmtran= $ran . $dmydate;
        $sort = substr($dmtran, 0, 16); 
        return $sort;
    }
}
