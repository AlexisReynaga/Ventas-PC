<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (session('api_user_role') !== 'admin') {
            // Responder JSON si espera API, sino redirigir al home con error flash
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No autorizado'], 403);
            }
            return redirect()->route('home')->withErrors(['auth' => 'No autorizado']);
        }
        return $next($request);
    }
}
