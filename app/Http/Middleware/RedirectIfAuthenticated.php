<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // 管理者ユーザーの場合は管理者画面にリダイレクト
                if (Auth::user()->is_admin) {
                    return redirect()->route('admin.attendance.list');
                }

                // 一般ユーザーの場合は勤怠打刻画面にリダイレクト
                return redirect()->route('attendance.index');
            }
        }

        return $next($request);
    }
}
