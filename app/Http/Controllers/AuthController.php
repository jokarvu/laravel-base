<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if(Auth::guest()) {
            // get data from requesst
            $data = $request->only('email', 'password');
            $remember_me = $request->input('remember');
            if(Auth::attempt($data, $remember_me)) {
                // if logged return success message
                return Response::json(['message' => 'Đăng nhập thành công!'], 200);
            }
            // if failed return error message
            return Response::json(['message' => 'Email hoặc mật khẩu không đúng!'], 401);
        }
        return Response::json(['message' => 'You have logged in'], 404);
    }

    public function isAdmin()
    {
        if(Auth::user()->can('access-admin-center')) { 
            return Response::json('true');
        }
        return Response::json('false', 403);
        // return Response::json('true');
    }

    public function current()
    {
        return Response::json(Auth::user());
    }

    public function logout()
    {
        Auth::logout();
        return Response::json(['message' => 'Đã đăng xuất']);
    }
}
