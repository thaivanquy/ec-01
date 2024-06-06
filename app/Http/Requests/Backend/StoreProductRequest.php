<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'regular_price' => str_replace('.', '', $this->regular_price),
            'compare_price' => str_replace('.', '', $this->compare_price),
            'barcode' => str_replace(' ', '', $this->barcode),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:3|max:100',
            'slug' => 'required|min:3|max:50|unique:products',
            'regular_price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|lt:regular_price',
            'barcode' => 'required|size:13|regex:/^[0-9]+$/',
            'category_id' => 'required|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => __('validation.required'),
            'title.min' => __('validation.min.string'),
            'title.max' => __('validation.max.string'),
            'slug.required' => __('validation.required'),
            'slug.unique' => __('validation.unique'),
            'slug.min' => __('validation.min.string'),
            'slug.max' => __('validation.max.string'),
            'regular_price.required' => __('validation.required'),
            'regular_price.numeric' => __('validation.numeric'),
            'regular_price.min' => __('validation.min.numeric'),
            'compare_price.numeric' => __('validation.numeric'),
            'compare_price.lt' => __('validation.lt.numeric'),
            'barcode.required' => __('validation.required'),
            'barcode.size' => __('validation.size'),
            'category_id.required' => __('validation.required'),
            'category_id.exists' => __('validation.exists'),
            'sub_category_id.exists' => __('validation.exists'),
            'brand_id.exists' => __('validation.exists'),
        ];
    }
}
