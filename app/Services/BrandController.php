<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}
