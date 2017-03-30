<?php

namespace App\Services;

class HelperService
{
    public function check($obj) {
        return isset($obj) && $obj != null && $obj != '';
    }
}
