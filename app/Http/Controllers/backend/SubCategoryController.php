<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\Backend\StoreSubCategoryRequest;
use App\Http\Requests\Backend\UpdateSubCategoryRequest;
use App\Services\SubCategoryService;
use App\Services\CategoryService;
use App\Models\SubCategory;
use \Illuminate\Http\Response;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $per_page = $request->per_page ?? 10;
        $keyword = $request->keyword ?? '';
        $status = $request->status ?? '';
        $category_id = $request->category_id ?? '';

        $params = [
            'page' => $page,
            'per_page' => $per_page,
            'keyword' => $keyword,
            'status' => $status,
            'category_id' => $category_id,
        ];

        $categories = CategoryService::getInstance()->getNameCategory();

        $subcategories = SubCategoryService::getInstance()->getList($params);
        $subcategories->appends($request->all());

        return view('backend.subcategories.index', compact('subcategories', 'categories'));
    }

    public function create()
    {
        $categories = CategoryService::getInstance()->getNameCategory();

        return view('backend.subcategories.create', compact('categories'));
    }

    public function store(StoreSubCategoryRequest $request)
    {
        try {
            $params = [
                'name' => $request->name,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'status' => $request->status,
            ];

            //create user
            SubCategoryService::getInstance()->storeSubCategory($params);
            
            if ($request->has('action') && $request->input('action') === 'save_and_new') {
                return redirect(route('backend.subcategories.create'))->with('success', __('message.create_successed'));
            }

            return redirect(route('backend.subcategories.index'))->with('success', __('message.create_successed'));
        } catch (\Exception $ex) {
            throw $ex;

            return redirect(route('backend.subcategories.index'))->with('error', __('message.create_failed'));
        }
    }

    public function detail($subcategoryInfo)
    {
        $subCategory = SubCategory::find($subcategoryInfo);

        if(!$subCategory) {
            return abort('404');
        }

        return view('backend.subcategories.detail', compact('subCategory'));
    }
    
    public function edit($subcategoryInfo)
    {
        $subCategory = SubCategory::find($subcategoryInfo);
        $categories = CategoryService::getInstance()->getNameCategory();

        if(!$subCategory) {
            return abort('404');
        }
        
        return view('backend.subcategories.edit', compact('subCategory', 'categories'));
    }

    public function update(UpdateSubCategoryRequest $request, $subcategoryInfo)
    {
        $subCategory = SubCategory::find($subcategoryInfo);

        if(!$subCategory) {
            return abort(404);
        }

        try {
            $params = [
                'name' => $request->name,
                'slug' => $request->slug,
                'category_id' => $request->category_id,
                'status' => $request->status,
            ];

            SubCategoryService::getInstance()->updateSubCategory($params, $subCategory);

            return redirect(route('backend.subcategories.detail', ['subcategoryInfo' => $subcategoryInfo]))->with('success', __('message.update_successed'));
        } catch (\Exception $ex) {
            throw $ex;

            return redirect(route('backend.subcategories.detail', ['subcategoryInfo' => $subcategoryInfo]))->with('error', __('message.update_failed'));
        }
    }

    public function delete($subcategoryInfo)
    {
        try {
            $subCategory = Category::find($subcategoryInfo);

            if(!$subCategory) {
                response()->json([
                    'message' => 'Error'
                ], Response::HTTP_NOT_FOUND);
            }

            SubCategoryService::getInstance()->deleteSubCategory($subCategory);

            return response()->json([
                'message' => 'Success'
            ], Response::HTTP_OK);
        } catch (\Exception $ex) {
            throw $ex;

            response()->json([
                'message' => 'Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
