<?php

namespace App\Services;

use App\Services\Traits\ServiceSingleton;
use DB;
use App\Models\Store;

class AttributeService
{
    use ServiceSingleton;

    public function getAllAttribute()
    {
        // return Attribute::select('id', 'name')->get();
    }

}
