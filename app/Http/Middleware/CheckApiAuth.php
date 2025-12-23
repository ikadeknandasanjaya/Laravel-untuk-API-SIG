<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiAuth
{
    /**
     * Handle an incoming request.
     * This middleware is deprecated - use auth:sanctum instead for API routes
     */
    public function handle(Request $request, Closure $next): Response
    {
        // This middleware is not used anymore - use auth:sanctum for bearer token auth
        return $next($request);
    }
}