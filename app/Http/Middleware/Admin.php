<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login'); // Chuyển hướng tới trang đăng nhập nếu chưa đăng nhập
        }

        return $next($request);
    }
}
