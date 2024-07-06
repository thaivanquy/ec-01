<?php

namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Services\CategoryService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class CategoriesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $params;
    private $row = 0;

    public function __construct($params)
    {
        $this->params = $params;
    }
    
    /**
    * @param Invoice $category
    */
    public function map($category): array
    {
        return [
            ++$this->row,
            $category->name,
            $category->slug,
            $category->image,
            ucfirst(config('common.status')[$category->status]),
            Carbon::parse($category->created_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAME',
            'SLUG',
            'IMAGE',
            'STATUS',
            'CREATED_AT',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CategoryService::getInstance()->getList($this->params);
    }
}
