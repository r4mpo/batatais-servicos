<?php

namespace Database\Seeders;

use App\Models\PracticeArea;
use Illuminate\Database\Seeder;

class PracticeAreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            [
                'title' => 'Segurança e vigilância',
                'description' => 'Profissionais para proteção patrimonial, portaria, eventos e escolta com treinamento e documentação adequados.',
            ],
            [
                'title' => 'Serviços domésticos e limpeza',
                'description' => 'Limpeza residencial, predial, pós-obra e organização de ambientes com produtos e métodos profissionais.',
            ],
            [
                'title' => 'Cuidados e bem-estar familiar',
                'description' => 'Apoio a famílias com cuidado infantil, acompanhamento de idosos e suporte no dia a dia do lar.',
            ],
            [
                'title' => 'Alimentação e gastronomia',
                'description' => 'Preparo de refeições, buffets, confeitaria e apoio em cozinhas residenciais e pequenos negócios.',
            ],
            [
                'title' => 'Tecnologia e informática',
                'description' => 'Suporte em hardware, software, redes, sites e soluções digitais para residências e empresas.',
            ],
            [
                'title' => 'Construção civil e obras',
                'description' => 'Reformas, acabamentos, alvenaria, gesso e serviços gerais de obra com segurança e qualidade.',
            ],
            [
                'title' => 'Instalações elétricas',
                'description' => 'Instalação e manutenção elétrica residencial, comercial e industrial em conformidade com normas.',
            ],
            [
                'title' => 'Instalações hidráulicas',
                'description' => 'Encanamento, aquecedores, caixas d’água, desentupimento e manutenção de sistemas hidráulicos.',
            ],
            [
                'title' => 'Saúde e bem-estar',
                'description' => 'Apoio à saúde com profissionais habilitados em cuidados complementares e bem-estar.',
            ],
            [
                'title' => 'Beleza e cuidados pessoais',
                'description' => 'Cabeleireiro, barbearia, estética, maquiagem e serviços de imagem pessoal.',
            ],
            [
                'title' => 'Automotivo e mecânica',
                'description' => 'Mecânica, elétrica automotiva, funilaria, lavagem e cuidados com veículos.',
            ],
            [
                'title' => 'Agronegócio e zona rural',
                'description' => 'Trabalho rural, manejo, colheita, irrigação e manutenção de propriedades no campo.',
            ],
            [
                'title' => 'Educação e aulas particulares',
                'description' => 'Reforço escolar, idiomas, música, informática e outras disciplinas com acompanhamento personalizado.',
            ],
            [
                'title' => 'Logística e transporte',
                'description' => 'Motoristas, entregas, carga e descarga e apoio operacional em deslocamentos e armazenagem.',
            ],
            [
                'title' => 'Administrativo e escritório',
                'description' => 'Digitação, arquivo, atendimento, contas a pagar/receber e rotinas administrativas.',
            ],
            [
                'title' => 'Hotelaria e turismo',
                'description' => 'Recepção, camareira, governança e apoio em hospedagem e serviços de turismo local.',
            ],
            [
                'title' => 'Eventos e entretenimento',
                'description' => 'Montagem, recepção, apoio técnico e operação para festas, feiras e apresentações.',
            ],
            [
                'title' => 'Pets e animais',
                'description' => 'Passeio, banho, tosa, adestramento e cuidados com animais domésticos.',
            ],
            [
                'title' => 'Jardinagem e paisagismo',
                'description' => 'Poda, plantio, irrigação, manutenção de gramados e pequenos projetos de paisagismo.',
            ],
            [
                'title' => 'Arte, comunicação e mídia',
                'description' => 'Design, fotografia, vídeo, redação e produção de conteúdo para marcas e pessoas.',
            ],
            [
                'title' => 'Metalurgia e solda',
                'description' => 'Solda, serralheria, estruturas metálicas e manutenção em equipamentos industriais leves.',
            ],
            [
                'title' => 'Mudanças e montagem',
                'description' => 'Transporte de mudanças, montagem de móveis, embalagem e organização de espaços.',
            ],
            [
                'title' => 'Serviços gerais e portal',
                'description' => 'Navegação e descoberta de categorias no diretório de profissionais de Batatais.',
            ],
        ];

        foreach ($areas as $row) {
            PracticeArea::query()->create($row);
        }
    }
}
