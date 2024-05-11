<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AttributeValueService;

class AttributeController extends Controller
{
    public function index(Request $request)
    {
        $attributeValues = AttributeValueService::getInstance()->getAttributeValue($request);
        
        $attributeValuesMapped = $attributeValues->map(function($attribute) {
            return [
                'id' => $attribute->id,
                'text' => $attribute->value,
            ];
        })->all();

        return response()->json([
            'status' => true,
            'items' => $attributeValuesMapped,
        ]);
    }
}
