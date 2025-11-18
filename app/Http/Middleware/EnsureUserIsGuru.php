<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsGuru
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isGuru()) {
            abort(403, 'Akses ditolak. Hanya guru yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}