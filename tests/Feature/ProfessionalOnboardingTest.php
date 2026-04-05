<?php

namespace Tests\Feature;

use App\Models\PracticeArea;
use App\Models\Profession;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfessionalOnboardingTest extends TestCase
{
    use RefreshDatabase;

    private function seedProfession(): Profession
    {
        $area = PracticeArea::query()->create([
            'title' => 'Área teste',
            'description' => 'Descrição',
        ]);

        return Profession::query()->create([
            'practice_area_id' => $area->id,
            'title' => 'Ofício teste',
            'description' => 'Descrição',
            'slug' => 'oficio-teste-'.uniqid(),
            'show_on_homepage' => false,
            'is_global_listing' => false,
        ]);
    }

    public function test_contractor_reaches_dashboard_without_redirect(): void
    {
        $user = User::factory()->create([
            'profile' => User::PROFILE_CONTRACTOR,
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();
    }

    public function test_professional_without_profile_is_redirected_from_dashboard_to_setup(): void
    {
        $user = User::factory()->professional()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('professional.setup'));
    }

    public function test_professional_can_view_setup_form(): void
    {
        $user = User::factory()->professional()->create();
        $this->seedProfession();

        $this->actingAs($user)
            ->get(route('professional.setup'))
            ->assertOk();
    }

    public function test_professional_can_submit_onboarding_and_reach_dashboard(): void
    {
        $profession = $this->seedProfession();
        $user = User::factory()->professional()->create([
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user)
            ->post(route('professional.setup.store'), [
                'profession_id' => $profession->id,
                'rg' => 'MG-12.345.678',
                'cpf' => '529.982.247-25',
                'cnpj' => '',
                'title' => 'Serviços de teste',
                'description' => 'Descrição do profissional.',
                'hourly_rate_reais' => '45,50',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertTrue(
            Professional::query()
                ->where('user_id', $user->id)
                ->where('cpf', '52998224725')
                ->exists()
        );
    }

    public function test_professional_with_existing_profile_can_view_setup_for_edit(): void
    {
        $profession = $this->seedProfession();
        $user = User::factory()->professional()->create();

        Professional::query()->create([
            'user_id' => $user->id,
            'profession_id' => $profession->id,
            'rg' => '12.345.678-9',
            'cpf' => '11144477735',
            'cnpj' => null,
            'title' => 'Já cadastrado',
            'description' => null,
            'hourly_rate_cents' => 5000,
        ]);

        $this->actingAs($user)
            ->get(route('professional.setup'))
            ->assertOk();
    }

    public function test_professional_can_update_existing_profile(): void
    {
        $profession = $this->seedProfession();
        $user = User::factory()->professional()->create();

        $pro = Professional::query()->create([
            'user_id' => $user->id,
            'profession_id' => $profession->id,
            'rg' => '12.345.678-9',
            'cpf' => '11144477735',
            'cnpj' => null,
            'title' => 'Título antigo',
            'description' => 'Antes.',
            'hourly_rate_cents' => 5000,
        ]);

        $this->actingAs($user)
            ->post(route('professional.setup.store'), [
                'profession_id' => $profession->id,
                'rg' => 'SP-98.765.432-1',
                'cpf' => '111.444.777-35',
                'cnpj' => '',
                'title' => 'Título novo',
                'description' => 'Depois.',
                'hourly_rate_reais' => '60,00',
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $pro->refresh();
        $this->assertSame('Título novo', $pro->title);
        $this->assertSame('Depois.', $pro->description);
        $this->assertSame(6000, $pro->hourly_rate_cents);
        $this->assertSame('SP-98.765.432-1', $pro->rg);
    }
}
