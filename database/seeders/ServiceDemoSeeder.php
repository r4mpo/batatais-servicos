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
            ],
            [
                'contractor' => $contractors[1],
                'status' => ServiceStatus::Concluded,
                'cents' => 320_00,
                'withdrawn' => false,
                'contractor_feedback' => null,
                'professional_feedback' => null,
                'created' => Carbon::now()->subDays(9),
            ],
            [
                'contractor' => $contractors[2],
                'status' => ServiceStatus::Concluded,
                'cents' => 280_00,
                'withdrawn' => true,
                'contractor_feedback' => 'Tudo certo, voltaria a contratar.',
                'professional_feedback' => null,
                'created' => Carbon::now()->subDays(45),
            ],
            [
                'contractor' => $contractors[3],
                'status' => ServiceStatus::Concluded,
                'cents' => 510_00,
                'withdrawn' => true,
                'contractor_feedback' => null,
                'professional_feedback' => 'Ótima experiência, pagamento rápido.',
                'created' => Carbon::now()->subDays(60),
            ],
            [
                'contractor' => $contractors[4],
                'status' => ServiceStatus::InProgress,
                'cents' => 200_00,
                'withdrawn' => false,
                'contractor_feedback' => null,
                'professional_feedback' => null,
                'created' => Carbon::now()->subDays(3),
            ],
            [
                'contractor' => $contractors[5],
                'status' => ServiceStatus::Support,
                'cents' => 150_00,
                'withdrawn' => false,
                'contractor_feedback' => 'Pequeno ajuste pendente no cronograma.',
                'professional_feedback' => 'Estamos alinhando retorno presencial.',
                'created' => Carbon::now()->subDays(6),
            ],
            [
                'contractor' => $contractors[0],
                'status' => ServiceStatus::PaymentPending,
                'cents' => 180_00,
                'withdrawn' => false,
                'contractor_feedback' => null,
                'professional_feedback' => null,
                'created' => Carbon::now()->subDay(),
            ],
        ];

        foreach ($rows as $row) {
            $service = new Service([
                'contractor_user_id' => $row['contractor']->id,
                'professional_user_id' => $professional->id,
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
