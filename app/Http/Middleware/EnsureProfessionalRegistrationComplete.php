<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfessionalRegistrationComplete
{
    /**
     * Profissionais (perfil {@see User::PROFILE_PROFESSIONAL}, valor "001" na base)
     * sem registro em `professionals` são direcionados ao cadastro completo.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user instanceof User) {
            return $next($request);
        }

        if (! $user->isProfessional()) {
            return $next($request);
        }

        if ($user->professionals()->exists()) {
            return $next($request);
        }

        if ($request->routeIs('professional.setup', 'professional.setup.store')) {
            return $next($request);
        }

        return redirect()->route('professional.setup');
    }
}
