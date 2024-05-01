<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use App\Http\Requests\Backend\StoreBrandRequest;
use App\Http\Requests\Backend\UpdateBrandRequest;

class BrandController extends Controller
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

        $brands = BrandService::getInstance()->getList($params);
        $brands->appends($request->all());

        return view('backend.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('backend.brands.create');
    }

    public function store(StoreBrandRequest $request)
    {
        try {
            $params = [
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
            ];
    
            //create user
            BrandService::getInstance()->storeBrand($params);
            
            if ($request->has('action') && $request->input('action') === 'save_and_new') {
                return redirect(route('backend.brands.create'))->with('success', __('message.create_successed'));
            }

            return redirect(route('backend.brands.index'))->with('success', __('message.create_successed'));
        } catch (\Exception $ex) {
            throw $ex;

            return redirect(route('backend.brands.index'))->with('error', __('message.create_failed'));
        }
    }

    public function detail($brandInfo)
    {
        $brand = Brand::find($brandInfo);

        if(!$brand) {
            return abort('404');
        }

        return view('backend.brands.detail', compact('brand'));
    }

    public function edit($brandInfo)
    {
        $brand = Brand::find($brandInfo);

        if(!$brand) {
            return abort('404');
        }

        return view('backend.brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, $brandInfo)
    {
        $brand = Brand::find($brandInfo);

        if(!$brand) {
            return abort(404);
        }

        try {
            $params = [
                'name' => $request->name,
                'slug' => $request->slug,
                'status' => $request->status,
            ];

            BrandService::getInstance()->updateBrand($params, $brand);

            return redirect(route('backend.brands.detail', ['brandInfo' => $brandInfo]))->with('success', __('message.update_successed'));
        } catch (\Exception $ex) {
            throw $ex;

            return redirect(route('backend.brands.detail', ['brandInfo' => $brandInfo]))->with('error', __('message.update_failed'));
        }
    }

    public function delete($brandInfo)
    {
        try {
            $brand = Brand::find($brandInfo);

            if(!$brand) {
                response()->json([
                    'message' => 'Error'
                ], Response::HTTP_NOT_FOUND);
            }

            BrandService::getInstance()->deleteBrand($brand);

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
