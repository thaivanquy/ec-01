<?php

namespace App\Services;

use App\Models\Ward;
use App\Services\Traits\ServiceSingleton;

class WardService
{
    use ServiceSingleton;

    public static function getWardByUser($wardCode)
    {
        return Ward::where('code', $wardCode )->first();
    }
}