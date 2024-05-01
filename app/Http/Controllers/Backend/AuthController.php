<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Backend\AuthRequest;
use Illuminate\Support\Facades\Auth;
use App\Enums\CommonEnum;

class AuthController extends Controller
{
    public function formLogin()
    {
        return view('backend.login');
    }

    public function processLogin(AuthRequest $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (\Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();

            if ($admin->role_id == CommonEnum::User || $admin->publish == CommonEnum::Inpublish) {
                \Auth::guard('admin')->logout();
                
                return redirect(route('backend.formLogin'))->with('error', 'You are not authorized to access administration');
            } else {
                return redirect(route('backend.dashboard'))->with('success', 'Login successful');
            }
        }

        return redirect(route('backend.formLogin'))->with('error', 'Login failed | Email or Password incorrect');
        
    }

    public function logout(Request $request) 
    {
        \Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        return redirect(route('backend.formLogin'));
    }
}
