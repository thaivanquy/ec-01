<?php

namespace App\Services;

use App\Services\Traits\ServiceSingleton;
use DB;
use App\Models\AttributeValue;

class AttributeValueService
{
    use ServiceSingleton;

    public function getAttributeValue($params)
    {
        return AttributeValue::whereHas('attribute', function ($query) use ($params) {
            $query->where('attribute_id', $params['option']['attributeId']);
        })->when(!empty($params['keyword']), function ($q) use ($params) {
            $q->where('value', 'like', '%' . $params['keyword'] . '%');
        })->get();
    }
}
