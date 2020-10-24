<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if ($request->user()->role == $roles) {
            return $next($request);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}