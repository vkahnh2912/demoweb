<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập trang quản trị.');
        }

        if ($request->user()->status !== 'active') {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị khóa hoặc chưa kích hoạt.');
        }

        if (! $request->user()->isAdmin()) {
            abort(403, 'Bạn không có quyền truy cập khu vực quản trị.');
        }

        return $next($request);
    }
}
