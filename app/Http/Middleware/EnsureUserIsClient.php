<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role?->name !== 'client') {
            abort(Response::HTTP_FORBIDDEN, 'Access denied. Clients only.');
        }

        return $next($request);
    }
}

