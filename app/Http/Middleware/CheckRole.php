<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        foreach ($roles as $role) {
            // Vérifier si l'utilisateur a le rôle requis
            if ($user->role === $role) {
                return $next($request);
            }
        }

        // Rediriger vers le tableau de bord avec un message d'erreur
        return redirect()->route('dashboard')
            ->with('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
    }
}
