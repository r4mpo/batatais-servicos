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
     * @param  Closure(Request): Response  $proximo  Próximo middleware/controller na pilha.
     */
    public function handle(Request $requisicao, Closure $proximo): Response
    {
        $usuario = $requisicao->user();
        if (! $usuario instanceof User) {
            return $proximo($requisicao);
        }

        if (! $usuario->isProfessional()) {
            return $proximo($requisicao);
        }

        if ($this->professionalRepository->existsForUserId($usuario->id)) {
            return $proximo($requisicao);
        }

        if ($requisicao->routeIs('professional.setup', 'professional.setup.store')) {
            return $proximo($requisicao);
        }

        return redirect()->route('professional.setup');
    }
}
