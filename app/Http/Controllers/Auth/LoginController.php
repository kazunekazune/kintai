<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * ログイン後のリダイレクト先を決定
     */
    public function redirectTo()
    {
        if (Auth::user()->is_admin) {
            return route('admin.attendance.list');
        }

        return route('attendance.index');
    }
}
