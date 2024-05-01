<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $this->categoryInfo . ''
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required'),
            'slug.required' => __('validation.required'),
            'slug.unique' => __('validation.unique'),
        ];
    }
}
