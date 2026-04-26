<?php

namespace Tests\Feature;

use App\Models\PracticeArea;
use App\Models\Professional;
use App\Models\ProfessionalFile;
use App\Models\ProfessionalVerificationRequest;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfessionalVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array{usuario: User, profissao: Profession, profissional: Professional}
     */
    private function criarDadosCompletosParaVerificacao(): array
    {
        $area = PracticeArea::query()->create([
            'title' => 'Área teste',
            'description' => 'D',
        ]);
        $profissao = Profession::query()->create([
            'practice_area_id' => $area->id,
            'title' => 'Ofício teste',
            'description' => 'D',
            'slug' => 'verific-'.uniqid(),
            'show_on_homepage' => false,
            'is_global_listing' => false,
            'verificacao_exige_cnh' => false,
            'verificacao_exige_certificado' => false,
            'verificacao_exige_diploma' => false,
        ]);
        $usuario = User::factory()->professional()->create([
            'profile_photo' => 'foto-h.jpg',
        ]);
        $texto = 'Descrição longa o suficiente para passar no mínimo exigido na verificação.';
        $p = Professional::query()->create([
            'user_id' => $usuario->id,
            'profession_id' => $profissao->id,
            'rg' => 'MG-12.3',
            'cpf' => '11144477735',
            'cnpj' => null,
            'title' => 'Título de teste',
            'description' => $texto,
            'hourly_rate_cents' => 5000,
        ]);
        foreach (['002', '003'] as $tipo) {
            ProfessionalFile::query()->create([
                'professional_id' => $p->id,
                'kind' => ProfessionalFile::KIND_VERIFICATION_DOCUMENT,
                'file_type' => $tipo,
                'disk' => 'local',
                'path' => "test/{$p->id}/doc-{$tipo}.jpg",
                'original_name' => 'a.jpg',
                'sort_order' => 0,
            ]);
        }

        return [
            'usuario' => $usuario,
            'profissao' => $profissao,
            'profissional' => $p,
        ];
    }

    public function test_rota_de_verificacao_requer_conta_de_profissional_com_cadastro(): void
    {
        $dados = $this->criarDadosCompletosParaVerificacao();
        $this->actingAs($dados['usuario'])
            ->get(route('professional.verificacao'))
            ->assertOk();
    }

    public function test_submissao_registra_solicitacao_pendente_quando_requisitos_atendidos(): void
    {
        $dados = $this->criarDadosCompletosParaVerificacao();
        $u = $dados['usuario'];

        $this->actingAs($u)
            ->post(route('professional.verificacao.store'), [
                'confirmo_termos' => '1',
            ])
            ->assertRedirect(route('professional.verificacao'))
            ->assertSessionHas('status', 'professional-verification-request-submitted');

        $this->assertTrue(
            ProfessionalVerificationRequest::query()
                ->where('user_id', $u->id)
                ->whereNull('approved')
                ->exists()
        );
    }

    public function test_submissao_retorna_faltas_quando_falta_descricao_suficiente(): void
    {
        $dados = $this->criarDadosCompletosParaVerificacao();
        $dados['profissional']->update(['description' => 'curto']);
        $u = $dados['usuario'];

        $this->actingAs($u)
            ->post(route('professional.verificacao.store'), [
                'confirmo_termos' => '1',
            ])
            ->assertRedirect(route('professional.verificacao'))
            ->assertSessionHas('requisitos_verificacao_faltando');
    }
}
