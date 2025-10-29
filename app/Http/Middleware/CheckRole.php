<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Maneja una solicitud entrante.
     * 
     * Este middleware verifica que el usuario autenticado tenga el rol requerido.
     * 
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role  El rol requerido ('admin', 'profesor', 'alumno')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Verificar si el usuario está autenticado
        if (!Auth::check()) {
            // No está autenticado, redirigir al login
            return redirect()->route('login');
        }

        // 2. Obtener el usuario autenticado
        $user = Auth::user();

        // 3. Verificar si el rol del usuario coincide con el rol requerido
        if ($user->rol !== $role) {
            // No tiene el rol correcto, redirigir al dashboard con mensaje de error
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        // 4. Todo correcto, permitir el acceso
        return $next($request);
    }
}