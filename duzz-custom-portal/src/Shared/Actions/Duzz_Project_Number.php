<?php

namespace Duzz\Shared\Actions;

class Duzz_Project_Number {
    public static function generate() {
        $yeardate = date("y");
        $monthdate = date("m");
        $daydate = date("d");
        $datehours = date("h");
        $dateminutes = date("i");
        $dateseconds = date("s");
        $zerodate = 0;
        $dmydate = $yeardate . $daydate . $monthdate . $datehours . $dateminutes . $dateseconds;
        $ran= rand(999,9999);
        $dmtran= $ran . $dmydate;    
        $sort=substr($dmtran, 0, 16); // if you want sort length code.
        return $sort;
    }
}
