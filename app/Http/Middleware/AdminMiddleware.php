<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // ยังไม่ล็อกอิน -> ไปหน้า login
        if (!Auth::check()) {
            return redirect()->guest(route('login'));
        }

        // ล็อกอินแล้วแต่ไม่ใช่ admin -> 403 (ไม่ redirect กันลูป)
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
