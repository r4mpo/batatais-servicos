<?php

namespace Database\Seeders;

use App\Enums\ServiceStatus;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ServiceDemoSeeder extends Seeder
{
    /**
     * Popula histórico de serviços para o primeiro profissional demo (João Costa).
     */
    public function run(): void
    {
        $professional = User::query()->where('email', 'profissional.demo.1@example.test')->first();
        if ($professional === null) {
            return;
        }

        if (Service::query()->where('professional_user_id', $professional->id)->exists()) {
            return;
        }

        $contractors = User::factory(6)->create([
            'profile' => User::PROFILE_CONTRACTOR,
        ]);

        $rows = [
            [
                'contractor' => $contractors[0],
                'status' => ServiceStatus::Concluded,
                'cents' => 450_00,
                'withdrawn' => false,
                'contractor_feedback' => 'Serviço impecável, pontual e muito educado.',
                'professional_feedback' => 'Cliente objetivo, recomendo.',
                'created' => Carbon::now()->subDays(12),
                'title' => 'Vigilância residencial — fim de semana',
                'description' => 'Plantão noturno e rondas no perímetro do condomínio; entrega de ocorrências por WhatsApp ao síndico.',
                'address_postal_code' => '14340000',
                'address_street' => 'Rua das Palmeiras',
                'address_number' => '120',
                'address_complement' => 'Bloco B',
                'address_neighborhood' => 'Centro',
                'address_city' => 'Batatais',
                'address_state' => 'SP',
                'scheduled_start_date' => Carbon::now()->subDays(14),
                'scheduled_end_date' => Carbon::now()->subDays(12),
                'scheduled_start_time' => '18:00:00',
                'scheduled_end_time' => '06:00:00',
            ],
            [
                'contractor' => $contractors[1],
                'status' => ServiceStatus::Concluded,
                'cents' => 320_00,
                'withdrawn' => false,
                'contractor_feedback' => null,
                'professional_feedback' => null,
                'created' => Carbon::now()->subDays(9),
                'title' => 'Apoio em evento corporativo',
                'description' => 'Controle de acesso ao salão, apoio ao estacionamento e orientação de convidados.',
                'address_postal_code' => '14300000',
                'address_street' => 'Av. Francisco Xavier',
                'address_number' => '450',
                'address_complement' => null,
                'address_neighborhood' => 'Jardim São Francisco',
                'address_city' => 'Batatais',
                'address_state' => 'SP',
                'scheduled_start_date' => Carbon::now()->subDays(10),
                'scheduled_end_date' => Carbon::now()->subDays(10),
                'scheduled_start_time' => '07:30:00',
                'scheduled_end_time' => '15:30:00',
            ],
            [
                'contractor' => $contractors[2],
                'status' => ServiceStatus::Concluded,
                'cents' => 280_00,
                'withdrawn' => true,
                'contractor_feedback' => 'Tudo certo, voltaria a contratar.',
                'professional_feedback' => null,
                'created' => Carbon::now()->subDays(45),
                'title' => 'Ronda em comércio local',
                'description' => 'Três visitas programadas à noite e registro fotográfico das portas.',
                'address_postal_code' => '14305000',
                'address_street' => 'Rua XV de Novembro',
                'address_number' => '88',
                'address_complement' => 'Loja 12',
                'address_neighborhood' => 'Centro',
                'address_city' => 'Batatais',
                'address_state' => 'SP',
                'scheduled_start_date' => Carbon::now()->subDays(47),
                'scheduled_end_date' => Carbon::now()->subDays(45),
                'scheduled_start_time' => '22:00:00',
                'scheduled_end_time' => '05:00:00',
            ],
            [
                'contractor' => $contractors[3],
                'status' => ServiceStatus::Concluded,
                'cents' => 510_00,
                'withdrawn' => true,
                'contractor_feedback' => null,
                'professional_feedback' => 'Ótima experiência, pagamento rápido.',
                'created' => Carbon::now()->subDays(60),
                'title' => 'Cobertura de feriado prolongado',
                'description' => 'Plantão 24h com revezamento acordado; foco em portaria e câmeras.',
                'address_postal_code' => '14315000',
                'address_street' => 'Rua Amazonas',
                'address_number' => '2100',
                'address_complement' => 'Portaria principal',
                'address_neighborhood' => 'Distrito Industrial',
                'address_city' => 'Batatais',
                'address_state' => 'SP',
                'scheduled_start_date' => Carbon::now()->subDays(64),
                'scheduled_end_date' => Carbon::now()->subDays(60),
                'scheduled_start_time' => '08:00:00',
                'scheduled_end_time' => '20:00:00',
            ],
            [
                'contractor' => $contractors[4],
                'status' => ServiceStatus::InProgress,
                'cents' => 200_00,
                'withdrawn' => false,
                'contractor_feedback' => null,
                'professional_feedback' => null,
                'created' => Carbon::now()->subDays(3),
                'title' => 'Segurança em obra',
                'description' => 'Controle de entrada de caminhões e conferência de notas fiscais de material.',
                'address_postal_code' => '14320000',
                'address_street' => 'Estrada Municipal Batatais-Brodowski',
                'address_number' => 's/n',
                'address_complement' => 'Canteiro km 3',
                'address_neighborhood' => 'Zona rural',
                'address_city' => 'Batatais',
                'address_state' => 'SP',
                'scheduled_start_date' => Carbon::now()->subDays(2),
                'scheduled_end_date' => Carbon::now()->addDays(5),
                'scheduled_start_time' => '06:00:00',
                'scheduled_end_time' => '18:00:00',
            ],
            [
                'contractor' => $contractors[5],
                'status' => ServiceStatus::Support,
                'cents' => 150_00,
                'withdrawn' => false,
                'contractor_feedback' => 'Pequeno ajuste pendente no cronograma.',
                'professional_feedback' => 'Estamos alinhando retorno presencial.',
                'created' => Carbon::now()->subDays(6),
                'title' => 'Suporte pós-instalação de câmeras',
                'description' => 'Revisão de ângulos e configuração de gravador; segunda visita a combinar.',
                'address_postal_code' => '14300000',
                'address_street' => 'Rua Tiradentes',
                'address_number' => '305',
                'address_complement' => 'Sala 2',
                'address_neighborhood' => 'Centro',
                'address_city' => 'Batatais',
                'address_state' => 'SP',
                'scheduled_start_date' => Carbon::now()->subDays(8),
                'scheduled_end_date' => Carbon::now()->addDays(2),
                'scheduled_start_time' => '14:00:00',
                'scheduled_end_time' => '17:00:00',
            ],
            [
                'contractor' => $contractors[0],
                'status' => ServiceStatus::PaymentPending,
                'cents' => 180_00,
                'withdrawn' => false,
                'contractor_feedback' => null,
                'professional_feedback' => null,
                'created' => Carbon::now()->subDay(),
                'title' => 'Plantão diurno — condomínio fechado',
                'description' => 'Aguardando confirmação de pagamento para início na data combinada.',
                'address_postal_code' => '14340000',
                'address_street' => 'Rua das Acácias',
                'address_number' => '45',
                'address_complement' => null,
                'address_neighborhood' => 'Jardim Aeroporto',
                'address_city' => 'Batatais',
                'address_state' => 'SP',
                'scheduled_start_date' => Carbon::now()->addDays(3),
                'scheduled_end_date' => Carbon::now()->addDays(3),
                'scheduled_start_time' => '07:00:00',
                'scheduled_end_time' => '19:00:00',
            ],
        ];

        foreach ($rows as $row) {
            $service = new Service([
                'contractor_user_id' => $row['contractor']->id,
                'professional_user_id' => $professional->id,
                'title' => $row['title'],
                'description' => $row['description'],
                'address_postal_code' => $row['address_postal_code'],
                'address_street' => $row['address_street'],
                'address_number' => $row['address_number'],
                'address_complement' => $row['address_complement'],
                'address_neighborhood' => $row['address_neighborhood'],
                'address_city' => $row['address_city'],
                'address_state' => $row['address_state'],
                'scheduled_start_date' => $row['scheduled_start_date'],
                'scheduled_end_date' => $row['scheduled_end_date'],
                'scheduled_start_time' => $row['scheduled_start_time'],
                'scheduled_end_time' => $row['scheduled_end_time'],
                'status' => $row['status'],
                'service_value_cents' => $row['cents'],
                'contractor_feedback' => $row['contractor_feedback'],
                'professional_feedback' => $row['professional_feedback'],
                'value_withdrawn' => $row['withdrawn'],
            ]);
            $service->created_at = $row['created'];
            $service->updated_at = $row['created']->copy()->addHours(4);
            $service->save();
        }
    }
}
