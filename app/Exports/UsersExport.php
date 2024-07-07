<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Services\UserService;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class UsersExport implements FromCollection, WithHeadings, WithMapping
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
    public function map($user): array
    {
        return [
            ++$this->row,
            $user->name,
            $user->email,
            $user->birthday,
            ucfirst(config('common.role')[$user->role_id]),
            $user->phone,
            ucfirst(config('common.gender')[$user->gender]),
            ucfirst(config('common.publish')[$user->publish]),
            optional($user->province)->name,
            optional($user->district)->name,
            optional($user->ward)->name,
            $user->address,
            Carbon::parse($user->created_at)->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'NO',
            'NAME',
            'EMAIL',
            'BIRTHDAY',
            'ROLE',
            'PHONE',
            'GENDER',
            'PUBLISH',
            'PROVINCE',
            'DISTRICT',
            'WARD',
            'ADDRESS',
            'CREATED_AT',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return UserService::getInstance()->getList($this->params);
    }
}
