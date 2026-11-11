<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek: Apakah user sudah login? DAN apakah role-nya 'admin'?
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            // Jika bukan admin, tolak akses dengan error 403
            abort(403, 'Akses Ditolak. Halaman ini hanya untuk Admin.');
        }

        return $next($request);
    }
}