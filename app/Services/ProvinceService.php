<?php

namespace App\Services;

use App\Models\Province;
use App\Services\Traits\ServiceSingleton;

class ProvinceService
{
    use ServiceSingleton;

    public function getAll()
    {
        $provinces = Province::all();

        return $provinces;
    }

    public function findByID(int $provinceID, array $column = ['*'], array $relation = [])
    {
        return Province::select($column)
            ->with($relation)
            ->findOrFail($provinceID);
    }

    public static function getProvinceByUser($provinceCode)
    {
        return Province::where('code', $provinceCode )->first();
    }
}
