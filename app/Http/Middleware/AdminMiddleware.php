<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {

            // if client is not logged in or already logged in as a user not admin
            if (Auth::guard('web')->check()) {
                return redirect('/');
            }
            // kalo dia admin yang belum login
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
