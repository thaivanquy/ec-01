<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoriesImport implements ToCollection, WithBatchInserts, SkipsEmptyRows, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            Category::create([
                'name' => $row['name'],
                'slug' => $row['slug'],
                'image' => '',
                'status' => 1,
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1; // Hàng tiêu đề 1 là đầu tiên
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug|regex:/^[a-z0-9\-]+$/',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' =>  __('validation.required'),
            'slug.required' =>  __('validation.required'),
            'slug.unique' => __('validation.unique'),
            'slug.regex' => __('validation.regex'),
        ];
    }
}
