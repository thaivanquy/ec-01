<?php

namespace App\Services;

use App\Services\Traits\ServiceSingleton;
use App\Models\District;

class DistrictService
{
    use ServiceSingleton;

    // public function findDistrictByProvinceId(int $provinceID = 0)
    // {
    //     return District::where('province_code', '=', $provinceID)->get();
    // }
    public function findByID(int $districtID, array $column = ['*'], array $relation = [])
    {
        return District::select($column)
            ->with($relation)
            ->findOrFail($districtID);
    }

    public static function getDistrictByUser($districtCode)
    {
        return District::where('code', $districtCode)->first();
    }
}
