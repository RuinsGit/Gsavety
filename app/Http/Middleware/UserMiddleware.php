<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Frontend kullanıcılarını doğrular
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'user') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu işlem için yetkiniz bulunmamaktadır.'
                ], 403);
            }
            
            return redirect()->route('login')->with('error', 'Bu işlem için yetkiniz bulunmamaktadır.');
        }

        return $next($request);
    }
} 