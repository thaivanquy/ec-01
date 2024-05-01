<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DistrictService;
use App\Services\ProvinceService;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $requestLocation = $request->input();

        $html = '';
        if ($requestLocation['target'] == 'district') {
            $province = ProvinceService::getInstance()->findByID($requestLocation['data']['location_id'], ['code', 'name'], ['districts']);
            $html = $this->renderHtml($province->districts);
        } else if ($requestLocation['target'] == 'ward') {
            $district = DistrictService::getInstance()->findByID($requestLocation['data']['location_id'], ['code', 'name'], ['wards']);
            $html = $this->renderHtml($district->wards, '[Select Ward]');
        }

        $response = [
            'html' => $html,
        ];
        
        return response()->json($response);
    }

    public function renderHtml($districts, $root = '[Select District]')
    {
        $html = '<option value="">' . $root . '</option>';
        foreach ($districts as $district) {
            $html .= '<option value="' . $district->code . '">' . $district->name . '</option>';
        }

        return $html;
    }
}
