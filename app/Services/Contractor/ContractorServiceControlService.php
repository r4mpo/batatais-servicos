<?php

namespace App\Services\Contractor;

use App\Enums\ServiceStatus;
use App\Http\Responses\ResultadoResposta;
use App\Models\Professional;
use App\Models\Service;
use App\Models\User;
use App\Repositories\ProfessionalRepository;
use App\Repositories\ServiceRepository;
use Illuminate\Http\Request;

/**
 * CRUD de serviços do contratante e transição simulada de pagamento.
 *
 * Aceite do profissional fica pronto em {@see self::aceitarPeloProfissional()} e ainda não tem rota.
 */
class ContractorServiceControlService
{
    public function __construct(
        private readonly ServiceRepository $serviceRepository,
        private readonly ProfessionalRepository $professionalRepository,
    ) {}

    public function listar(mixed $usuario, Request $request): ResultadoResposta
    {
        if ($negado = $this->negarSeNaoForContratante($usuario)) {
            return $negado;
        }

        $filters = $this->filtros($request);

        $services = $this->serviceRepository->paginateForOwner(
            'contractor_user_id',
            (int) $usuario->id,
            $filters,
            'professionalUser',
            ['professionalUser:id,name'],
        );

        return ResultadoResposta::pagina('contractor.services.index', [
            'services' => $services,
            'filters' => $filters,
            'filtersActive' => $filters['q'] !== '' || $filters['status'] !== '' || $filters['sort'] !== 'recent',
            'statuses' => ServiceStatus::cases(),
        ]);
    }

    public function montarCriacao(mixed $usuario): ResultadoResposta
    {
        if ($negado = $this->negarSeNaoForContratante($usuario)) {
            return $negado;
        }

        return ResultadoResposta::pagina('contractor.services.form', [
            'selectedProfessional' => $this->profissionalSelecionado(null),
            'service' => null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $validado
     */
    public function criar(mixed $usuario, array $validado): ResultadoResposta
    {
        if ($negado = $this->negarSeNaoForContratante($usuario)) {
            return $negado;
        }

        $professionalUserId = $this->userIdDoProfissional($validado['professional_id'] ?? null);
        if ($professionalUserId === false) {
            return ResultadoResposta::erroHttp(422);
        }

        $service = $this->serviceRepository->create([
            ...$this->atributosPersistiveis($validado),
            'contractor_user_id' => $usuario->id,
            'professional_user_id' => $professionalUserId,
            'status' => ServiceStatus::PaymentPending,
            'value_withdrawn' => false,
        ]);

        return ResultadoResposta::redirecionar(
            'contractor.services.show',
            ['service' => $service->id],
            'contractor-service-created',
        );
    }

    public function exibir(mixed $usuario, Service $service): ResultadoResposta
    {
        if ($negado = $this->negarSeNaoForContratante($usuario)) {
            return $negado;
        }
        if ($ausente = $this->negarSeNaoForDono($usuario, $service)) {
            return $ausente;
        }

        $this->serviceRepository->loadProfessionalUser($service);

        return ResultadoResposta::pagina('contractor.services.show', [
            'service' => $service,
        ]);
    }

    public function montarEdicao(mixed $usuario, Service $service): ResultadoResposta
    {
        if ($negado = $this->negarSeNaoForContratante($usuario)) {
            return $negado;
        }
        if ($ausente = $this->negarSeNaoForDono($usuario, $service)) {
            return $ausente;
        }
        if ($service->status !== ServiceStatus::PaymentPending) {
            return ResultadoResposta::erroHttp(403);
        }

        $fallbackId = $service->professional_user_id
            ? $this->professionalRepository->findIdByUserId((int) $service->professional_user_id)
            : null;

        return ResultadoResposta::pagina('contractor.services.form', [
            'selectedProfessional' => $this->profissionalSelecionado($fallbackId),
            'service' => $service,
        ]);
    }

    /**
     * @param  array<string, mixed>  $validado
     */
    public function atualizar(mixed $usuario, Service $service, array $validado): ResultadoResposta
    {
        if ($negado = $this->negarSeNaoForContratante($usuario)) {
            return $negado;
        }
        if ($ausente = $this->negarSeNaoForDono($usuario, $service)) {
            return $ausente;
        }
        if ($service->status !== ServiceStatus::PaymentPending) {
            return ResultadoResposta::erroHttp(403);
        }

        $professionalUserId = $this->userIdDoProfissional($validado['professional_id'] ?? null);
        if ($professionalUserId === false) {
            return ResultadoResposta::erroHttp(422);
        }

        $this->serviceRepository->update($service, [
            ...$this->atributosPersistiveis($validado),
            'professional_user_id' => $professionalUserId,
        ]);

        return ResultadoResposta::redirecionar(
            'contractor.services.show',
            ['service' => $service->id],
            'contractor-service-updated',
        );
    }

    public function excluir(mixed $usuario, Service $service): ResultadoResposta
    {
        if ($negado = $this->negarSeNaoForContratante($usuario)) {
            return $negado;
        }
        if ($ausente = $this->negarSeNaoForDono($usuario, $service)) {
            return $ausente;
        }
        if ($service->status !== ServiceStatus::PaymentPending) {
            return ResultadoResposta::erroHttp(403);
        }

        $this->serviceRepository->delete($service);

        return ResultadoResposta::redirecionar('contractor.services.index', status: 'contractor-service-deleted');
    }

    public function marcarComoPago(mixed $usuario, Service $service): ResultadoResposta
    {
        if ($negado = $this->negarSeNaoForContratante($usuario)) {
            return $negado;
        }
        if ($ausente = $this->negarSeNaoForDono($usuario, $service)) {
            return $ausente;
        }
        if ($service->status !== ServiceStatus::PaymentPending) {
            return ResultadoResposta::erroHttp(403);
        }

        $this->serviceRepository->update($service, ['status' => ServiceStatus::UnderReview]);

        return ResultadoResposta::redirecionar(
            'contractor.services.show',
            ['service' => $service->id],
            'contractor-service-paid',
        );
    }

    /**
     * Transição reservada para quando o profissional puder aceitar o serviço.
     */
    public function aceitarPeloProfissional(Service $service): void
    {
        if ($service->status !== ServiceStatus::UnderReview) {
            return;
        }

        $this->serviceRepository->update($service, ['status' => ServiceStatus::AcceptedByProfessional]);
    }

    /**
     * @return int|null|false null quando o contratante deixa para depois; false se o id não for de um profissional
     */
    private function userIdDoProfissional(mixed $professionalId): int|null|false
    {
        if ($professionalId === null || $professionalId === '') {
            return null;
        }

        $professional = $this->professionalRepository->findWithUser((int) $professionalId);
        if (! $professional instanceof Professional || ! $professional->user?->isProfessional()) {
            return false;
        }

        return (int) $professional->user_id;
    }

    public function buscarProfissionais(mixed $usuario, string $termo): ResultadoResposta
    {
        if (! $usuario instanceof User || ! $usuario->isContractor()) {
            return ResultadoResposta::erroHttp(403, 'Access Denied');
        }

        $termo = trim($termo);
        if (mb_strlen($termo) < 2) {
            return ResultadoResposta::json([]);
        }

        $profissionais = $this->professionalRepository->searchByNameOrProfession($termo);

        return ResultadoResposta::json($profissionais->map(fn (Professional $professional) => [
            'id' => $professional->id,
            'name' => $professional->user->name,
            'profession' => $professional->profession->title,
        ])->all());
    }

    private function profissionalSelecionado(?int $fallbackId): ?Professional
    {
        $old = old('professional_id');
        $id = $old !== null ? ($old === '' ? null : (int) $old) : $fallbackId;
        if (! $id) {
            return null;
        }

        return $this->professionalRepository->findWithUserAndProfession($id);
    }

    /**
     * @param  array<string, mixed>  $validado
     * @return array<string, mixed>
     */
    private function atributosPersistiveis(array $validado): array
    {
        return [
            'title' => $validado['title'],
            'description' => $validado['description'] ?? null,
            'service_value_cents' => (int) round((float) $validado['service_value_reais'] * 100),
            'scheduled_start_date' => $validado['scheduled_start_date'],
            'scheduled_end_date' => $validado['scheduled_end_date'],
            'scheduled_start_time' => $validado['scheduled_start_time'] ?? null,
            'scheduled_end_time' => $validado['scheduled_end_time'] ?? null,
            'address_postal_code' => $validado['address_postal_code'] ?? null,
            'address_street' => $validado['address_street'] ?? null,
            'address_number' => $validado['address_number'] ?? null,
            'address_complement' => $validado['address_complement'] ?? null,
            'address_neighborhood' => $validado['address_neighborhood'] ?? null,
            'address_city' => $validado['address_city'] ?? null,
            'address_state' => $validado['address_state'] ?? null,
        ];
    }

    private function negarSeNaoForContratante(mixed $usuario): ?ResultadoResposta
    {
        if (! $usuario instanceof User || ! $usuario->isContractor()) {
            return ResultadoResposta::erroHttp(403, 'Access Denied');
        }

        return null;
    }

    private function negarSeNaoForDono(User $usuario, Service $service): ?ResultadoResposta
    {
        if ((int) $service->contractor_user_id !== (int) $usuario->id) {
            return ResultadoResposta::erroHttp(403, 'Access Denied');
        }

        return null;
    }

    /**
     * @return array{q: string, status: string, sort: string}
     */
    private function filtros(Request $request): array
    {
        $status = ServiceStatus::tryFrom((int) $request->query('status', 0));
        $sort = (string) $request->query('sort', 'recent');
        if (! in_array($sort, ['recent', 'oldest', 'value_desc', 'value_asc'], true)) {
            $sort = 'recent';
        }

        return [
            'q' => trim((string) $request->query('q', '')),
            'status' => $status !== null ? (string) $status->value : '',
            'sort' => $sort,
        ];
    }
}
