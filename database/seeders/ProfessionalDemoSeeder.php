<?php

namespace Database\Seeders;

use App\Models\Professional;
use App\Models\ProfessionalAvailability;
use App\Models\ProfessionalReview;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfessionalDemoSeeder extends Seeder
{
    public function run(): void
    {
        $professionsBySlug = Profession::query()
            ->whereIn('slug', [
                'vigia',
                'seguranca',
                'costureira',
                'ti',
                'baba',
                'faxineira',
                'domestica',
                'cozinheira',
                'jardineiro',
                'porteiro',
                'diarista',
                'vigilante-noturno',
            ])
            ->get()
            ->keyBy('slug');

        if ($professionsBySlug->isEmpty()) {
            return;
        }

        $reviewers = User::factory(35)->create([
            'profile' => User::PROFILE_CONTRACTOR,
        ]);

        $rows = [
            ['name' => 'João Costa', 'slug' => 'vigia', 'title' => 'Vigia experiente', 'description' => 'Vigia experiente com 10 anos de profissão. Segurança residencial e comercial.', 'hourly_rate_cents' => 3334],
            ['name' => 'Maria Silva', 'slug' => 'faxineira', 'title' => 'Limpeza residencial e comercial', 'description' => 'Limpeza profissional com 8 anos de experiência. Residências e comercial.', 'hourly_rate_cents' => 6250],
            ['name' => 'Roberto Pereira', 'slug' => 'ti', 'title' => 'TI e suporte técnico', 'description' => 'Suporte técnico, manutenção de computadores e redes. Atendimento rápido.', 'hourly_rate_cents' => 10000],
            ['name' => 'Ana Ferreira', 'slug' => 'baba', 'title' => 'Babá certificada', 'description' => 'Babá experiente com certificação. Cuidado responsável com crianças.', 'hourly_rate_cents' => 5000],
            ['name' => 'Carlos Gomes', 'slug' => 'seguranca', 'title' => 'Segurança para eventos', 'description' => 'Segurança profissional para eventos e empresas. Treinado e certificado.', 'hourly_rate_cents' => 4167],
            ['name' => 'Lucia Rocha', 'slug' => 'costureira', 'title' => 'Costura e consertos', 'description' => 'Costura profissional, consertos e confecção. Trabalho de qualidade garantida.', 'hourly_rate_cents' => 2500],
            ['name' => 'Fernanda Alves', 'slug' => 'domestica', 'title' => 'Serviços gerais no lar', 'description' => 'Organização, limpeza e apoio no dia a dia da casa.', 'hourly_rate_cents' => 3500],
            ['name' => 'Paulo Mendes', 'slug' => 'cozinheira', 'title' => 'Cozinha para eventos', 'description' => 'Preparo de refeições e apoio em festas e residências.', 'hourly_rate_cents' => 4500],
            ['name' => 'Ricardo Souza', 'slug' => 'jardineiro', 'title' => 'Jardinagem e manutenção', 'description' => 'Poda, capina e cuidado com jardins e quintais.', 'hourly_rate_cents' => 3000],
            ['name' => 'Eduardo Lima', 'slug' => 'porteiro', 'title' => 'Portaria e recepção', 'description' => 'Controle de acesso e atendimento em condomínios.', 'hourly_rate_cents' => 2800],
            ['name' => 'Juliana Prado', 'slug' => 'diarista', 'title' => 'Diárias de limpeza', 'description' => 'Limpeza pesada e organização por diária.', 'hourly_rate_cents' => 3800],
            ['name' => 'Marcos Teixeira', 'slug' => 'vigilante-noturno', 'title' => 'Plantão noturno', 'description' => 'Vigilância e rondas em período noturno.', 'hourly_rate_cents' => 4000],
            ['name' => 'Patricia Nunes', 'slug' => 'ti', 'title' => 'Redes e computadores', 'description' => 'Instalação de redes Wi-Fi e manutenção de PCs.', 'hourly_rate_cents' => 8500],
            ['name' => 'Gabriel Ribeiro', 'slug' => 'faxineira', 'title' => 'Limpeza pós-obra', 'description' => 'Limpeza técnica após reformas e obras leves.', 'hourly_rate_cents' => 5500],
        ];

        foreach ($rows as $index => $row) {
            $profession = $professionsBySlug->get($row['slug']);
            if (! $profession) {
                continue;
            }

            $user = User::factory()->create([
                'name' => $row['name'],
                'email' => 'profissional.demo.'.($index + 1).'@example.test',
                'profile' => User::PROFILE_CLIENT,
            ]);

            $professional = Professional::query()->create([
                'user_id' => $user->id,
                'profession_id' => $profession->id,
                'title' => $row['title'],
                'description' => $row['description'],
                'hourly_rate_cents' => $row['hourly_rate_cents'],
            ]);

            $this->seedReviews($professional, $reviewers);
            $this->seedAvailabilities($professional, $index);
        }
    }

    /**
     * @param  \Illuminate\Support\Collection<int, User>  $reviewers
     */
    private function seedReviews(Professional $professional, $reviewers): void
    {
        $count = random_int(5, 12);
        $n = min($count, $reviewers->count());

        foreach ($reviewers->shuffle()->take($n) as $reviewer) {
            ProfessionalReview::query()->create([
                'user_id' => $reviewer->id,
                'professional_id' => $professional->id,
                'rating' => random_int(4, 5),
                'comment' => null,
            ]);
        }
    }

    private function seedAvailabilities(Professional $professional, int $index): void
    {
        $todayDow = (int) now()->dayOfWeek;

        for ($d = 1; $d <= 5; $d++) {
            ProfessionalAvailability::query()->create([
                'professional_id' => $professional->id,
                'day_of_week' => $d,
                'starts_at' => '08:00:00',
                'ends_at' => '18:00:00',
                'is_full_day' => false,
            ]);
        }

        if ($index % 4 === 0) {
            ProfessionalAvailability::query()->create([
                'professional_id' => $professional->id,
                'day_of_week' => $todayDow,
                'starts_at' => null,
                'ends_at' => null,
                'is_full_day' => true,
            ]);
        } elseif (! $this->weekdayHasSlot($professional, $todayDow)) {
            ProfessionalAvailability::query()->create([
                'professional_id' => $professional->id,
                'day_of_week' => $todayDow,
                'starts_at' => '09:00:00',
                'ends_at' => '17:00:00',
                'is_full_day' => false,
            ]);
        }

        if ($index % 5 === 0) {
            ProfessionalAvailability::query()->create([
                'professional_id' => $professional->id,
                'day_of_week' => 0,
                'starts_at' => null,
                'ends_at' => null,
                'is_full_day' => true,
            ]);
        }
    }

    private function weekdayHasSlot(Professional $professional, int $dow): bool
    {
        return ProfessionalAvailability::query()
            ->where('professional_id', $professional->id)
            ->where('day_of_week', $dow)
            ->exists();
    }
}
