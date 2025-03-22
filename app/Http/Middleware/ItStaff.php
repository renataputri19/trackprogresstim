<?php

// app/Http/Middleware/ItStaff.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ItStaff
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->is_it_staff) {
            abort(403, 'Unauthorized: Only IT staff can access this page.');
        }
        return $next($request);
    }
}
