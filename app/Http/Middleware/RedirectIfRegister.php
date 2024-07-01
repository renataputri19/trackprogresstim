<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfRegister
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('register')) {
            return redirect('/');
        }

        return $next($request);
    }
}

