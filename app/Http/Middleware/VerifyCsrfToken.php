<?php

namespace CVS\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'api/*'
    ];

    public function handle($request, Closure $next)
    {
        if (app()->environment() == 'testing') {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
