<?php

namespace App\Services;

use App\Models\User;
use App\Services\Traits\ServiceSingleton;
use DB;

class UserService
{
    use ServiceSingleton;

    public function __construct()
    {

    }

    public function getList($params)
    {
        $users = User::when(!empty($params['keyword']), function ($q) use ($params) {
            $q->where('name', 'like', '%' . $params['keyword'] . '%')
                ->orWhere('email', 'like', '%' . $params['keyword'] . '%')
                ->orWhere('phone', 'like', '%' . $params['keyword'] . '%')
                ->orWhere('address', 'like', '%' . $params['keyword'] . '%');
        })
        ->when(!empty($params['role_id']), function ($q) use ($params) {
            $q->where('role_id', $params['role_id']);
        })
        ->paginate($params['per_page'] ?? null, ['*'], 'page', $params['page'] ?? null);
    
        return $users;
    }

    public function storeUser($params)
    {
        DB::beginTransaction();

        try {
            User::create($params);

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function updateUser($params, $userInfo)
    {
        DB::beginTransaction();

        try {
            User::where('id', $userInfo->id)->update($params);

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function deleteUser($userInfo)
    {
        DB::beginTransaction();

        try {
            User::where('id', $userInfo->id)->delete();

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function changePublishByUser($publish, $userInfo)
    {
        DB::beginTransaction();

        try {
            User::where('id', $userInfo->id)->update([
                'publish' => (($publish == 0) ? 1 : 0)
            ]);

            DB::commit();

        } catch (\Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }
}
