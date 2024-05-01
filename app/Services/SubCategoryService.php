<?php

namespace App\Services;

use App\Services\Traits\ServiceSingleton;
use App\Models\SubCategory;
use DB;

class SubCategoryService
{
    use ServiceSingleton;

    public function getList($params)
    {
        $subcategories = SubCategory::when(!empty($params['keyword']), function ($q) use ($params) {
            $q->where('name', 'like', '%' . $params['keyword'] . '%');
        })
        ->when(!empty($params['status']), function ($q) use ($params) {
            $q->where('status', $params['status']);
        })
        ->when(!empty($params['category_id']), function ($q) use ($params) {
            $q->whereHas('category', function ($query) use ($params) {
                $query->where('category_id', $params['category_id']);
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($params['per_page'] ?? null, ['*'], 'page', $params['page'] ?? null);
    
        return $subcategories;
    }

    public function storeSubCategory($params)
    {
        DB::beginTransaction();

        try {
            SubCategory::create($params);

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function updateSubCategory($params, $subcategoryInfo)
    {
        DB::beginTransaction();

        try {
            SubCategory::where('id', $subcategoryInfo->id)->update($params);

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function deleteSubCategory($subcategoryInfo)
    {
        DB::beginTransaction();

        try {
            SubCategory::where('id', $subcategoryInfo->id)->delete();

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
