<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\StoreCategoryRequest;
use App\Http\Requests\Backend\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use \Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CategoriesExport;
use App\Imports\CategoriesImport;
use App\Http\Requests\Backend\ImportCategoriesRequest;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $per_page = $request->per_page ?? 10;
        $keyword = $request->keyword ?? '';
        $status = $request->status ?? '';

        $params = [
            'page' => $page,
            'per_page' => $per_page,
            'keyword' => $keyword,
            'status' => $status,
        ];

        $categories = CategoryService::getInstance()->getList($params);
        $categories->appends($request->all());

        return view('backend.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.categories.create');
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $params = [
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
            ];
    
            //create user
            CategoryService::getInstance()->storeCategory($params);
            
            if ($request->has('action') && $request->input('action') === 'save_and_new') {
                return redirect(route('backend.categories.create'))->with('success', __('message.create_successed'));
            }

            return redirect(route('backend.categories.index'))->with('success', __('message.create_successed'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect(route('backend.categories.index'))->with('error', __('message.create_failed'));
        }
    }

    public function detail($categoryInfo)
    {
        $category = Category::find($categoryInfo);

        if(!$category) {
            return abort('404');
        }

        return view('backend.categories.detail', compact('category'));
    }

    public function edit($categoryInfo)
    {
        $category = Category::find($categoryInfo);

        if(!$category) {
            return abort('404');
        }

        return view('backend.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, $categoryInfo)
    {
        $category = Category::find($categoryInfo);

        if(!$category) {
            return abort(404);
        }

        try {
            $params = [
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
            ];

            CategoryService::getInstance()->updateCategory($params, $category);

            return redirect(route('backend.categories.detail', ['categoryInfo' => $categoryInfo]))->with('success', __('message.update_successed'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect(route('backend.categories.detail', ['categoryInfo' => $categoryInfo]))->with('error', __('message.update_failed'));
        }
    }

    public function delete($categoryInfo)
    {
        try {
            $category = Category::find($categoryInfo);

            if(!$category) {
                response()->json([
                    'message' => 'Error'
                ], Response::HTTP_NOT_FOUND);
            }

            CategoryService::getInstance()->deleteCategory($category);

            return response()->json([
                'message' => 'Success'
            ], Response::HTTP_OK);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            response()->json([
                'message' => 'Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function import(ImportCategoriesRequest $request)
    {
        try {
            // $file = $request->file('file');
            // $fileName = time() . '_' . $file->getClientOriginalName();
            // $file->storeAs('public/file/', $fileName);

            // $filePath = storage_path('app/public/file/' . $fileName);
            Excel::import(new CategoriesImport, $request->file('file'));

            return redirect(route('backend.categories.index'))->with('success', __('message.import_successed'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            
            return redirect(route('backend.categories.index'))->with('error', $ex->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $page = $request->page ?? 1;
            $per_page = $request->per_page ?? 10;
            $keyword = $request->keyword ?? '';
            $status = $request->status ?? '';
    
            $params = [
                'page' => $page,
                'per_page' => $per_page,
                'keyword' => $keyword,
                'status' => $status,
            ];

            return Excel::download(new CategoriesExport($params), 'categories.csv');
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect(route('backend.categories.index'))->with('error', __('message.export_failed'));
        }
    }
}
