<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!$request->user()->actif) {
            auth()->logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été désactivé. Contactez l\'administrateur.',
            ]);
        }

        if (!empty($roles) && !in_array($request->user()->role, $roles)) {
            abort(403, 'Accès non autorisé. Vous n\'avez pas les permissions requises.');
        }

        return $next($request);
    }
}
