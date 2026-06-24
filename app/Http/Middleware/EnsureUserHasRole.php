<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            abort(403);
        }

        if (! in_array(auth()->user()->role->name, $roles)) {
            abort(403);
        }

        return $next($request);
    }
}