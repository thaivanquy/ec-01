<?php

namespace App\Services;

use App\Services\Traits\ServiceSingleton;
use App\Models\Category;
use DB;

class CategoryService
{
    use ServiceSingleton;

    public function getList($params)
    {
        $categories = Category::when(!empty($params['keyword']), function ($q) use ($params) {
            $q->where('name', 'like', '%' . $params['keyword'] . '%');
        })
        ->when(!empty($params['status']), function ($q) use ($params) {
            $q->where('status', $params['status']);
        })
        ->paginate($params['per_page'] ?? null, ['*'], 'page', $params['page'] ?? null);
    
        return $categories;
    }

    public function storeCategory($params)
    {
        DB::beginTransaction();

        try {
            Category::create($params);

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function updateCategory($params, $categoryInfo)
    {
        DB::beginTransaction();

        try {
            Category::where('id', $categoryInfo->id)->update($params);

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function deleteCategory($categoryInfo)
    {
        DB::beginTransaction();

        try {
            Category::where('id', $categoryInfo->id)->delete();

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
