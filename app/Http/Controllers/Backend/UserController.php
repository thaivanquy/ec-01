<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ProvinceService;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\StoreUserRequest;
use App\Http\Requests\Backend\UpdateUserRequest;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $per_page = $request->per_page ?? 10;
        $keyword = $request->keyword ?? '';
        $role_id = $request->role_id ?? '';

        $params = [
            'role_id' => $role_id,
            'keyword' => $keyword,
            'per_page' => $per_page,
            'page' => $page,
        ];

        $users = UserService::getInstance()->getList($params);
        $users->appends($request->all());

        return view('backend.users.index', compact('users'));
    }

    public function create()
    {
        $provinces = ProvinceService::getInstance()->getAll();

        return view('backend.users.create', compact('provinces'));
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $params = [
                'email' => $request->email,
                'name' => $request->name,
                'role_id' => $request->role_id,
                'birthday' => $request->birthday,
                'password' => Hash::make($request->password),
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'gender' => $request->gender,
                'description' => $request->description,
            ];
            
            if ($request->avatar) {
                $avatar = $request->avatar;
                $imageName = time() . '_' . $avatar->getClientOriginalName();
                // dd($imageName);
                $avatar->storeAs('public/images', $imageName);

                //Save under Database
                $params['avatar'] = $imageName;
            }

            //create user
            UserService::getInstance()->storeUser($params);
            
            if ($request->has('action') && $request->input('action') === 'save_and_new') {
                return redirect(route('backend.users.create'))->with('success', __('message.create_successed'));
            }

            return redirect(route('backend.users.index'))->with('success', __('message.create_successed'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect(route('backend.users.index'))->with('error', __('message.create_failed'));
        }

    }

    public function detail($userInfo)
    {
        $user = User::find($userInfo);
        $provinces = ProvinceService::getInstance()->getAll();

        if(!$user) {
            return abort('404');
        }

        return view('backend.users.detail', compact('user', 'provinces'));
    }

    public function edit($userInfo)
    {
        $user = User::find($userInfo);
        $provinces = ProvinceService::getInstance()->getAll();

        if(!$user) {
            return abort('404');
        }

        return view('backend.users.edit', compact('user', 'provinces'));
    }

    public function update(UpdateUserRequest $request, $userInfo)
    {
        $user = User::find($userInfo);

        if(!$user) {
            return abort(404);
        }

        try {
            $params = [
                'email' => $request->email,
                'name' => $request->name,
                'role_id' => $request->role_id,
                'gender' => $request->gender,
                'province_id' => $request->province_id,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'birthday' => $request->birthday,
                'description' => $request->description,
            ];

            if ($request->avatar) {
                $avatar = $request->avatar;
                $imageName = time() . '_' . $avatar->getClientOriginalName();
                $avatar->storeAs('public/images', $imageName);

                //Save under Database
                $params['avatar'] = $imageName;
            }

            UserService::getInstance()->updateUser($params, $user);

            return redirect(route('backend.users.detail', ['userInfo' => $userInfo]))->with('success', __('message.update_successed'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            return redirect(route('backend.users.detail', ['userInfo' => $userInfo]))->with('error', __('message.update_failed'));
        }
    }
    
    public function delete(Request $request, $userInfo)
    {
        try {
            $user = User::find($userInfo);

            if(!$user) {
                response()->json([
                    'message' => 'Error'
                ], Response::HTTP_NOT_FOUND);
            }

            UserService::getInstance()->deleteUser($user);

            return response()->json([
                'message' => 'Success'
            ], Response::HTTP_OK);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            response()->json([
                'message' => 'Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changePublish(Request $request)
    {
        
        try {
            $params = [
                'publish' => $request->publish,
                'user_id' => $request->user_id,
            ];

            $user = User::find($params['user_id']);

            if(!$user) {
                response()->json([
                    'message' => 'Error'
                ], Response::HTTP_NOT_FOUND);
            }

            UserService::getInstance()->changePublishByUser($params['publish'], $user);

            return response()->json([
                'message' => 'Success'
            ], Response::HTTP_OK);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());

            response()->json([
                'message' => 'Error'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
