<?php

namespace App\Services;

use App\Services\Traits\ServiceSingleton;
use DB;
use App\Enums\CommonEnum;
use App\Models\Brand;

class BrandService
{
    use ServiceSingleton;

    public function getList($params)
    {
        $brands = Brand::when(!empty($params['keyword']), function ($q) use ($params) {
            $q->where('name', 'like', '%' . $params['keyword'] . '%');
        })
        ->when(!empty($params['status']), function ($q) use ($params) {
            $q->where('status', $params['status']);
        })
        ->orderBy('created_at', 'desc')
        ->paginate($params['per_page'] ?? null, ['*'], 'page', $params['page'] ?? null);
    
        return $brands;
    }

    public function storeBrand($params)
    {
        DB::beginTransaction();

        try {
            Brand::create($params);

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function updateBrand($params, $brandInfo)
    {
        DB::beginTransaction();

        try {
            Brand::where('id', $brandInfo->id)->update($params);

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function deleteBrand($brandInfo)
    {
        DB::beginTransaction();

        try {
            Brand::where('id', $brandInfo->id)->delete();

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function getBrands()
    {
        return Brand::orderBy('name', 'ASC')->get();
    }
}
