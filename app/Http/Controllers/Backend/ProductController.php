<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AttributeService;
use App\Services\CategoryService;
use App\Services\BrandService;
use App\Http\Requests\Backend\StoreProductRequest;

class ProductController extends Controller
{
    public function create()
    {
        $categories = CategoryService::getInstance()->getNameCategory();
        $brands = BrandService::getInstance()->getBrands();
        $attributes = AttributeService::getInstance()->getAllAttribute();

        return view('backend.products.create', compact('attributes', 'categories', 'brands'));
    }

    public function store(StoreProductRequest $request)
    {
        dd($request->toArray());
    }
}
