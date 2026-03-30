<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Si es admin, redirige al panel
            if (Auth::user()->email === env('ADMIN_EMAIL', 'admin@artesmar.com')) {
                return redirect()->route('admin.inscripciones');
            }
            // Si es usuario regular, redirige a inscripciones
            return redirect()->route('inscripciones');
        }

        return $next($request);
    }
}
