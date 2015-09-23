<?php

namespace CVS\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsOrganizer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! Auth::check() || Auth::user()->organizer != true) {
            return response()->json('Unauthorized (not authenticated or missing permissions).', 401);
        }

        return $next($request);
    }
}
