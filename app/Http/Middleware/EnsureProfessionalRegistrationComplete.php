<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Repositories\ProfessionalRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Garante que contas com perfil profissional tenham cadastro em `professionals` antes de usar o restante da área logada.
 *
 * A verificação de existência do registro usa apenas o {@see ProfessionalRepository}.
 */
class EnsureProfessionalRegistrationComplete
{
    public function __construct(
        private readonly ProfessionalRepository $professionalRepository,
    ) {}

    /**
     * Redireciona para o setup quando falta cadastro; libera rotas do próprio setup e demais perfis sem alteração.
     *
     * @param  Closure(Request): Response  $next  Próximo middleware/controller na pilha.
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

        if ($this->professionalRepository->existsForUserId($user->id)) {
            return $next($request);
        }

        if ($request->routeIs('professional.setup', 'professional.setup.store')) {
            return $next($request);
        }

        return redirect()->route('professional.setup');
    }
}
