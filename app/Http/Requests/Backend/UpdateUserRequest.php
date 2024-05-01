<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'email' => 'required|email:filter|unique:users,email,' . $this->userInfo . ',id',
            'name' => 'required|string|max:100',
            'role_id' => 'required|integer|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('validation.required'),
            'email.email' => __('validation.email'),
            'email.unique' => __('validation.unique'),
            'name.required' => __('validation.required'),
            'name.string' => __('validation.string'),
            'name.max' => __('validation.max'),
            'role_id.required' => __('validation.required'),
            'role_id.integer' => __('validation.integer'),
            'role_id.gt' => __('validation.gt'),
        ];
    }
}
