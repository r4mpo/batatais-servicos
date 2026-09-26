<?php

namespace Tests\Feature;

use App\Enums\ServiceStatus;
use App\Http\Responses\ResultadoResposta;
use App\Models\PracticeArea;
use App\Models\Profession;
use App\Models\Professional;
use App\Models\Service;
use App\Models\User;
use App\Services\Contractor\ContractorServiceControlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceControlAndProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_professional_history_filters_by_status_and_keeps_query_on_pagination(): void
    {
        $professionalUser = $this->professionalAccount();
        $ana = User::factory()->create(['name' => 'Ana Contratante']);
        $bruno = User::factory()->create(['name' => 'Bruno Contratante']);

        $this->serviceFor($ana, $professionalUser, 'Pintura da Ana', ServiceStatus::PaymentPending, 10000);
        $this->serviceFor($bruno, $professionalUser, 'Elétrica do Bruno', ServiceStatus::Concluded, 20000);

        for ($i = 0; $i < 12; $i++) {
            $this->serviceFor($ana, $professionalUser, 'Extra '.$i, ServiceStatus::PaymentPending, 3000);
        }

        $this->actingAs($professionalUser)
            ->get(route('professional.services.history', ['q' => 'Ana', 'status' => ServiceStatus::PaymentPending->value]))
            ->assertOk()
            ->assertSee('Extra 0')
            ->assertDontSee('Elétrica do Bruno')
            ->assertSee('q=Ana', false);
    }

    public function test_public_profile_is_linked_from_the_directory(): void
    {
        $professionalUser = $this->professionalAccount();
        $professional = $professionalUser->professionals()->first();

        $this->get(route('professionals.index'))
            ->assertOk()
            ->assertSee(route('professionals.show', $professional), false);

        $this->get(route('professionals.show', $professional))
            ->assertOk()
            ->assertSee('Bio pública do teste')
            ->assertDontSee(__('labels.professional_profile_hire'));
    }

    public function test_contractor_can_create_a_service_and_assign_the_professional_later(): void
    {
        $contractor = User::factory()->create();
        $professional = $this->professionalAccount()->professionals()->first();

        $this->actingAs($contractor)
            ->get(route('contractor.services.index'))
            ->assertOk()
            ->assertSee(route('contractor.services.create'), false);

        $this->actingAs($contractor)
            ->get(route('contractor.services.create'))
            ->assertOk()
            ->assertSee(__('labels.contractor_services_professional_later'))
            ->assertSee('contractor-service-form.js', false);

        $this->actingAs($contractor)
            ->post(route('contractor.services.store'), [
                'professional_id' => '',
                'title' => 'Serviço sem profissional',
                'service_value_reais' => '80,00',
                'scheduled_start_date' => '2026-10-01',
                'scheduled_end_date' => '2026-10-01',
            ])
            ->assertRedirect();

        $service = Service::query()->where('title', 'Serviço sem profissional')->first();
        $this->assertNotNull($service);
        $this->assertNull($service->professional_user_id);

        $this->actingAs($contractor)
            ->put(route('contractor.services.update', $service), [
                'professional_id' => $professional->id,
                'title' => 'Serviço sem profissional',
                'service_value_reais' => '80,00',
                'scheduled_start_date' => '2026-10-01',
                'scheduled_end_date' => '2026-10-01',
            ])
            ->assertRedirect(route('contractor.services.show', $service));

        $this->assertSame($professional->user_id, $service->fresh()->professional_user_id);
    }

    public function test_only_the_owner_can_open_the_edit_page(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $professionalUser = $this->professionalAccount();
        $service = $this->serviceFor($owner, $professionalUser, 'Serviço do dono', ServiceStatus::PaymentPending, 9000);

        $this->actingAs($owner)
            ->get(route('contractor.services.edit', $service))
            ->assertOk()
            ->assertSee(__('labels.contractor_services_edit_title'))
            ->assertSee('Serviço do dono');

        $service->setRawAttributes(array_merge($service->getAttributes(), [
            'contractor_user_id' => (string) $owner->id,
        ]), true);

        $resultado = app(ContractorServiceControlService::class)->montarEdicao($owner, $service);
        $this->assertSame(ResultadoResposta::PAGINA, $resultado->tipo);

        $this->actingAs($other)
            ->get(route('contractor.services.edit', $service))
            ->assertForbidden()
            ->assertSee('Access Denied');
    }

    public function test_service_list_shows_payment_status_delete_modal_and_pagination(): void
    {
        $contractor = User::factory()->create();
        $professionalUser = $this->professionalAccount();
        $service = $this->serviceFor($contractor, $professionalUser, 'Aguardando pagamento', ServiceStatus::PaymentPending, 4000);

        $this->actingAs($contractor)
            ->get(route('contractor.services.index'))
            ->assertOk()
            ->assertSee('service-pay-status--pending', false)
            ->assertSee(__('labels.service_status_payment_pending'))
            ->assertSee('contractor-delete-modal', false)
            ->assertSee(__('labels.pagination_page_of', ['current' => 1, 'last' => 1]))
            ->assertSee(__('pagination.previous'))
            ->assertDontSee('&laquo;', false)
            ->assertSee(route('contractor.services.edit', $service), false);
    }

    public function test_contractor_creates_service_pays_and_cannot_edit_afterwards(): void
    {
        $contractor = User::factory()->create();
        $professionalUser = $this->professionalAccount();
        $professional = $professionalUser->professionals()->first();

        $this->actingAs($contractor)
            ->post(route('contractor.services.store'), [
                'professional_id' => $professional->id,
                'title' => 'Pintura da sala',
                'description' => 'Paredes',
                'service_value_reais' => '150,00',
                'scheduled_start_date' => '2026-10-01',
                'scheduled_end_date' => '2026-10-02',
                'scheduled_start_time' => '08:00',
                'scheduled_end_time' => '12:00',
                'address_city' => 'Batatais',
                'address_state' => 'sp',
            ])
            ->assertRedirect();

        $service = Service::query()->first();
        $this->assertNotNull($service);
        $this->assertSame(ServiceStatus::PaymentPending, $service->status);
        $this->assertSame(15000, $service->service_value_cents);
        $this->assertSame('SP', $service->address_state);

        $this->actingAs($contractor)
            ->post(route('contractor.services.pay', $service))
            ->assertRedirect(route('contractor.services.show', $service));

        $service->refresh();
        $this->assertSame(ServiceStatus::UnderReview, $service->status);

        $this->actingAs($contractor)
            ->get(route('contractor.services.edit', $service))
            ->assertForbidden();
    }

    public function test_accept_method_moves_under_review_service_forward(): void
    {
        $contractor = User::factory()->create();
        $professionalUser = $this->professionalAccount();
        $service = $this->serviceFor($contractor, $professionalUser, 'Revisão', ServiceStatus::UnderReview, 5000);

        app(ContractorServiceControlService::class)->aceitarPeloProfissional($service);

        $this->assertSame(ServiceStatus::AcceptedByProfessional, $service->fresh()->status);
    }

    public function test_contractor_dashboard_shows_history_and_illustrated_messages(): void
    {
        $contractor = User::factory()->create();

        $this->actingAs($contractor)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee(__('labels.dashboard_contractor_stats_title'))
            ->assertSee(__('labels.dashboard_contractor_stats_total'))
            ->assertSee(__('labels.dashboard_contractor_history_title'))
            ->assertSee(__('labels.dashboard_card_messages_title'))
            ->assertSee(__('labels.dashboard_card_messages_text'))
            ->assertDontSee(__('labels.dashboard_card_professional_title'));
    }

    public function test_professional_search_returns_matches_from_the_backend(): void
    {
        $contractor = User::factory()->create();
        $professional = $this->professionalAccount()->professionals()->first();

        $this->actingAs($contractor)
            ->getJson(route('contractor.professionals.search', ['q' => 'Pintor']))
            ->assertOk()
            ->assertJsonFragment([
                'id' => $professional->id,
                'name' => 'Profissional Público',
                'profession' => 'Pintor',
            ]);

        $this->actingAs($contractor)
            ->getJson(route('contractor.professionals.search', ['q' => 'x']))
            ->assertOk()
            ->assertExactJson([]);
    }

    public function test_end_schedule_cannot_be_before_the_start(): void
    {
        $contractor = User::factory()->create();

        $this->actingAs($contractor)
            ->from(route('contractor.services.create'))
            ->post(route('contractor.services.store'), [
                'title' => 'Horário inválido',
                'service_value_reais' => '10,00',
                'scheduled_start_date' => '2026-10-01',
                'scheduled_end_date' => '2026-10-01',
                'scheduled_start_time' => '14:00',
                'scheduled_end_time' => '09:00',
            ])
            ->assertRedirect(route('contractor.services.create'))
            ->assertSessionHasErrors('scheduled_end_time');

        $this->assertNull(Service::query()->where('title', 'Horário inválido')->first());
    }

    public function test_contractor_dashboard_sums_service_amounts(): void
    {
        $contractor = User::factory()->create();
        $professionalUser = $this->professionalAccount();
        $this->serviceFor($contractor, $professionalUser, 'Pendente', ServiceStatus::PaymentPending, 10000);
        $this->serviceFor($contractor, $professionalUser, 'Andamento', ServiceStatus::InProgress, 20000);
        $this->serviceFor($contractor, $professionalUser, 'Feito', ServiceStatus::Concluded, 30000);

        $this->actingAs($contractor)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('100,00')
            ->assertSee('200,00')
            ->assertSee('300,00')
            ->assertSee('600,00');
    }

    private function professionalAccount(): User
    {
        $user = User::factory()->professional()->create(['name' => 'Profissional Público']);
        $area = PracticeArea::query()->create([
            'title' => 'Área teste',
            'description' => 'Descrição',
        ]);
        $profession = Profession::query()->create([
            'practice_area_id' => $area->id,
            'title' => 'Pintor',
            'description' => 'Ofício',
            'slug' => 'pintor-'.uniqid(),
            'show_on_homepage' => false,
            'is_global_listing' => false,
        ]);
        Professional::query()->create([
            'user_id' => $user->id,
            'profession_id' => $profession->id,
            'title' => 'Pintura residencial',
            'description' => 'Bio pública do teste',
            'hourly_rate_cents' => 8000,
        ]);

        return $user;
    }

    private function serviceFor(User $contractor, User $professionalUser, string $title, ServiceStatus $status, int $cents): Service
    {
        return Service::query()->create([
            'contractor_user_id' => $contractor->id,
            'professional_user_id' => $professionalUser->id,
            'title' => $title,
            'status' => $status,
            'service_value_cents' => $cents,
            'value_withdrawn' => false,
        ]);
    }
}
