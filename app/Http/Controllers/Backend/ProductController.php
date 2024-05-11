<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AttributeService;

class ProductController extends Controller
{
    public function create()
    {
        $attributes = AttributeService::getInstance()->getAllAttribute();

        return view('backend.products.create', compact('attributes'));
    }
}
