<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SlugController extends Controller
{
    public function index(Request $request)
    {
        $slug = '';
        if(!empty($request->title)) {
            $slug = Str::slug($request->title);
        }

        return response()->json([
            'status' => true,
            'slug' => $slug,
        ]);
    }
}
