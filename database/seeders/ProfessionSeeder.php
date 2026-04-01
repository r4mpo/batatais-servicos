<?php

namespace Database\Seeders;

use App\Models\PracticeArea;
use App\Models\Profession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProfessionSeeder extends Seeder
{
    public function run(): void
    {
        $areaId = PracticeArea::query()->orderBy('id')->pluck('id', 'title');

        $rows = $this->professionDefinitions();

        foreach ($rows as $row) {
            $title = $row['title'];
            $areaTitle = $row['area'];

            if (! isset($areaId[$areaTitle])) {
                continue;
            }

            $slug = $row['slug'] ?? $this->uniqueSlug($title, $row['slug_prefix'] ?? null);

            Profession::query()->create([
                'practice_area_id' => $areaId[$areaTitle],
                'title' => $title,
                'description' => $row['description'],
                'slug' => $slug,
                'icon' => $row['icon'] ?? null,
                'show_on_homepage' => $row['show_on_homepage'] ?? false,
                'is_global_listing' => $row['is_global_listing'] ?? false,
            ]);
        }
    }

    private function uniqueSlug(string $title, ?string $prefix = null): string
    {
        $base = Str::slug(Str::ascii($prefix ? $prefix.'-'.$title : $title));
        $slug = $base;
        $n = 2;

        while (Profession::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$n;
            $n++;
        }

        return $slug;
    }

    /**
     * @return list<array{
     *     area: string,
     *     title: string,
     *     description: string,
     *     slug?: string,
     *     slug_prefix?: string,
     *     icon?: string|null,
     *     show_on_homepage?: bool,
     *     is_global_listing?: bool
     * }>
     */
    private function professionDefinitions(): array
    {
        return array_merge(
            $this->securityProfessions(),
            $this->domesticProfessions(),
            $this->familyCareProfessions(),
            $this->foodProfessions(),
            $this->itProfessions(),
            $this->constructionProfessions(),
            $this->electricalProfessions(),
            $this->plumbingProfessions(),
            $this->healthProfessions(),
            $this->beautyProfessions(),
            $this->automotiveProfessions(),
            $this->agricultureProfessions(),
            $this->educationProfessions(),
            $this->logisticsProfessions(),
            $this->adminProfessions(),
            $this->hospitalityProfessions(),
            $this->eventsProfessions(),
            $this->petsProfessions(),
            $this->gardenProfessions(),
            $this->creativeProfessions(),
            $this->metalProfessions(),
            $this->movingProfessions(),
            $this->globalListingCard(),
        );
    }

    private function securityProfessions(): array
    {
        $a = 'Segurança e vigilância';

        return [
            ['area' => $a, 'title' => 'Vigias', 'description' => 'Profissionais experientes para segurança residencial e comercial com responsabilidade.', 'slug' => 'vigia', 'icon' => 'fas fa-shield-alt', 'show_on_homepage' => true],
            ['area' => $a, 'title' => 'Seguranças', 'description' => 'Segurança profissional para eventos, empresas e residências com certificação.', 'slug' => 'seguranca', 'icon' => 'fas fa-user-secret', 'show_on_homepage' => true],
            ['area' => $a, 'title' => 'Vigilante noturno', 'description' => 'Rondas e monitoramento em período noturno para condomínios e empresas.', 'slug' => 'vigilante-noturno', 'icon' => 'fas fa-moon'],
            ['area' => $a, 'title' => 'Porteiro', 'description' => 'Controle de acesso, recebimento e registro de visitantes em portarias.', 'slug' => 'porteiro', 'icon' => 'fas fa-door-open'],
            ['area' => $a, 'title' => 'Agente de prevenção de perdas', 'description' => 'Apoio ao varejo com foco em prevenção de furtos e atendimento ao cliente.', 'slug' => 'agente-prevencao-perdas', 'icon' => 'fas fa-eye'],
            ['area' => $a, 'title' => 'Monitoramento de CFTV', 'description' => 'Operação e análise de câmeras e alarmes em central ou local.', 'slug' => 'monitoramento-cftv', 'icon' => 'fas fa-video'],
            ['area' => $a, 'title' => 'Escolta e transporte de valores', 'description' => 'Procedimentos seguros para deslocamento de valores e documentos sensíveis.', 'slug' => 'escolta-valores', 'icon' => 'fas fa-truck'],
            ['area' => $a, 'title' => 'Supervisor de segurança', 'description' => 'Coordenação de equipes, postos de serviço e relatórios operacionais.', 'slug' => 'supervisor-seguranca', 'icon' => 'fas fa-clipboard-list'],
            ['area' => $a, 'title' => 'Segurança para eventos', 'description' => 'Fluxo de público, credenciamento e apoio em shows, feiras e festas.', 'slug' => 'seguranca-eventos', 'icon' => 'fas fa-users'],
            ['area' => $a, 'title' => 'Brigadista', 'description' => 'Prevenção e primeiras ações em emergências conforme projeto de incêndio.', 'slug' => 'brigadista', 'icon' => 'fas fa-fire-extinguisher'],
            ['area' => $a, 'title' => 'Consultoria em segurança', 'description' => 'Análise de risco, procedimentos e adequação de processos patrimoniais.', 'slug' => 'consultoria-seguranca', 'icon' => 'fas fa-user-shield'],
        ];
    }

    private function domesticProfessions(): array
    {
        $a = 'Serviços domésticos e limpeza';

        return [
            ['area' => $a, 'title' => 'Costureiras', 'description' => 'Serviços de costura, consertos e confecção de roupas com qualidade garantida.', 'slug' => 'costureira', 'icon' => 'fas fa-scissors', 'show_on_homepage' => true],
            ['area' => $a, 'title' => 'Faxineiras', 'description' => 'Limpeza profissional para residências e comercial com produtos de qualidade.', 'slug' => 'faxineira', 'icon' => 'fas fa-broom', 'show_on_homepage' => true],
            ['area' => $a, 'title' => 'Domésticas', 'description' => 'Serviços gerais de limpeza e organização do lar com dedicação.', 'slug' => 'domestica', 'icon' => 'fas fa-home', 'show_on_homepage' => true],
            ['area' => $a, 'title' => 'Diarista', 'description' => 'Limpeza por diária com foco em banheiros, cozinha e áreas comuns.', 'slug' => 'diarista', 'icon' => 'fas fa-spray-can'],
            ['area' => $a, 'title' => 'Passadeira', 'description' => 'Passadoria de roupas sociais e camas com acabamento profissional.', 'slug' => 'passadeira', 'icon' => 'fas fa-tshirt'],
            ['area' => $a, 'title' => 'Limpeza pós-obra', 'description' => 'Remoção de resíduos, limpeza pesada e preparação do imóvel para uso.', 'slug' => 'limpeza-pos-obra', 'icon' => 'fas fa-hard-hat'],
            ['area' => $a, 'title' => 'Limpeza de vidros', 'description' => 'Fachadas, vitrines e esquadrias com equipamentos e produtos adequados.', 'slug' => 'limpeza-vidros', 'icon' => 'fas fa-window-maximize'],
            ['area' => $a, 'title' => 'Organizadora de ambientes', 'description' => 'Métodos de organização, rotulagem e otimização de espaços residenciais.', 'slug' => 'organizadora-ambientes', 'icon' => 'fas fa-boxes'],
            ['area' => $a, 'title' => 'Lavadeira', 'description' => 'Lavagem, secagem e tratamento de roupas e enxovais.', 'slug' => 'lavadeira', 'icon' => 'fas fa-soap'],
            ['area' => $a, 'title' => 'Camareira residencial', 'description' => 'Arrumação de quartos, troca de roupas de cama e apoio em residências.', 'slug' => 'camareira-residencial', 'icon' => 'fas fa-bed'],
            ['area' => $a, 'title' => 'Zeladora', 'description' => 'Manutenção da limpeza em condomínios e áreas comuns com escala definida.', 'slug' => 'zeladora', 'icon' => 'fas fa-building'],
        ];
    }

    private function familyCareProfessions(): array
    {
        $a = 'Cuidados e bem-estar familiar';

        return [
            ['area' => $a, 'title' => 'Babás', 'description' => 'Cuidado profissional e responsável com crianças com experiência comprovada.', 'slug' => 'baba', 'icon' => 'fas fa-child', 'show_on_homepage' => true],
            ['area' => $a, 'title' => 'Cuidador de idosos', 'description' => 'Acompanhamento, higiene, medicamentos sob orientação e convivência diária.', 'slug' => 'cuidador-idosos', 'icon' => 'fas fa-hand-holding-heart'],
            ['area' => $a, 'title' => 'Doula', 'description' => 'Apoio físico e emocional no pré-natal, parto e pós-parto.', 'slug' => 'doula', 'icon' => 'fas fa-heart'],
            ['area' => $a, 'title' => 'Babá overnight', 'description' => 'Plantões noturnos para bebês e crianças pequenas.', 'slug' => 'baba-noturna', 'icon' => 'fas fa-moon'],
            ['area' => $a, 'title' => 'Acompanhante terapêutico', 'description' => 'Suporte em deslocamentos e atividades conforme orientação especializada.', 'slug' => 'acompanhante-terapeutico', 'icon' => 'fas fa-walking'],
            ['area' => $a, 'title' => 'Responsável por recreação infantil', 'description' => 'Brincadeiras, oficinas e atividades lúdicas em festas e grupos.', 'slug' => 'recreacao-infantil', 'icon' => 'fas fa-puzzle-piece'],
            ['area' => $a, 'title' => 'Caseiro', 'description' => 'Manutenção de propriedades de campo, horta e pequenos reparos.', 'slug' => 'caseiro', 'icon' => 'fas fa-tree'],
        ];
    }

    private function foodProfessions(): array
    {
        $a = 'Alimentação e gastronomia';

        return [
            ['area' => $a, 'title' => 'Cozinheiras', 'description' => 'Preparo de refeições e serviços de catering com receitas especiais.', 'slug' => 'cozinheira', 'icon' => 'fas fa-utensils', 'show_on_homepage' => true],
            ['area' => $a, 'title' => 'Chef de cozinha', 'description' => 'Cardápios, mise en place e execução de pratos para eventos e residências.', 'slug' => 'chef-cozinha', 'icon' => 'fas fa-fire'],
            ['area' => $a, 'title' => 'Confeiteira', 'description' => 'Bolos, doces finos e sobremesas para aniversários e celebrações.', 'slug' => 'confeiteira', 'icon' => 'fas fa-birthday-cake'],
            ['area' => $a, 'title' => 'Garçom', 'description' => 'Atendimento em eventos, buffet e restaurantes com protocolo de serviço.', 'slug' => 'garcom', 'icon' => 'fas fa-concierge-bell'],
            ['area' => $a, 'title' => 'Churrasqueiro', 'description' => 'Assados, cortes e operação de churrasqueiras em eventos e residências.', 'slug' => 'churrasqueiro', 'icon' => 'fas fa-drumstick-bite'],
            ['area' => $a, 'title' => 'Bartender', 'description' => 'Preparo de drinks, coquetéis e bar em festas e estabelecimentos.', 'slug' => 'bartender', 'icon' => 'fas fa-cocktail'],
            ['area' => $a, 'title' => 'Auxiliar de cozinha', 'description' => 'Apoio em pré-preparo, higienização e organização da cozinha.', 'slug' => 'auxiliar-cozinha', 'icon' => 'fas fa-carrot'],
            ['area' => $a, 'title' => 'Personal chef', 'description' => 'Menus personalizados e refeições no domicílio do cliente.', 'slug' => 'personal-chef', 'icon' => 'fas fa-utensil-spoon'],
        ];
    }

    private function itProfessions(): array
    {
        $a = 'Tecnologia e informática';

        return [
            ['area' => $a, 'title' => 'Profissionais de TI', 'description' => 'Suporte técnico, manutenção e consultoria em informática com expertise.', 'slug' => 'ti', 'icon' => 'fas fa-laptop', 'show_on_homepage' => true],
            ['area' => $a, 'title' => 'Técnico de informática', 'description' => 'Formatação, upgrades, periféricos e diagnóstico de hardware.', 'slug' => 'tecnico-informatica', 'icon' => 'fas fa-desktop'],
            ['area' => $a, 'title' => 'Suporte em redes', 'description' => 'Wi-Fi, cabeamento, roteadores e pequenas redes corporativas.', 'slug' => 'suporte-redes', 'icon' => 'fas fa-network-wired'],
            ['area' => $a, 'title' => 'Desenvolvedor web', 'description' => 'Sites, landing pages e integrações sob medida para negócios locais.', 'slug' => 'desenvolvedor-web', 'icon' => 'fas fa-code'],
            ['area' => $a, 'title' => 'Consultor de software', 'description' => 'Escolha de ferramentas, processos e treinamento de equipes.', 'slug' => 'consultor-software', 'icon' => 'fas fa-cogs'],
            ['area' => $a, 'title' => 'Instalador de câmeras IP', 'description' => 'CFTV residencial e comercial com acesso remoto e armazenamento.', 'slug' => 'instalador-cameras-ip', 'icon' => 'fas fa-video'],
            ['area' => $a, 'title' => 'Recuperação de dados', 'description' => 'Backup, cópias de segurança e tentativa de recuperação em discos.', 'slug' => 'recuperacao-dados', 'icon' => 'fas fa-database'],
            ['area' => $a, 'title' => 'Instrutor de informática', 'description' => 'Aulas de pacote Office, internet e ferramentas básicas para idosos e iniciantes.', 'slug' => 'instrutor-informatica', 'icon' => 'fas fa-chalkboard-teacher'],
        ];
    }

    private function constructionProfessions(): array
    {
        $a = 'Construção civil e obras';

        return [
            ['area' => $a, 'title' => 'Pedreiro', 'description' => 'Alvenaria, reboco, contrapisos e pequenas estruturas de alvenaria.', 'slug' => 'obra-pedreiro', 'icon' => 'fas fa-hammer'],
            ['area' => $a, 'title' => 'Servente de obras', 'description' => 'Apoio em transporte de materiais, limpeza de canteiro e auxílio a oficiais.', 'slug' => 'servente-obras', 'icon' => 'fas fa-dolly'],
            ['area' => $a, 'title' => 'Pintor', 'description' => 'Pintura interna e externa, massa corrida e acabamentos.', 'slug' => 'pintor', 'icon' => 'fas fa-paint-roller'],
            ['area' => $a, 'title' => 'Gesseiro', 'description' => 'Forros, sancas, drywall e reparos em gesso.', 'slug' => 'gesseiro', 'icon' => 'fas fa-square'],
            ['area' => $a, 'title' => 'Azulejista', 'description' => 'Assentamento de pisos e revestimentos cerâmicos e porcelanatos.', 'slug' => 'azulejista', 'icon' => 'fas fa-th-large'],
            ['area' => $a, 'title' => 'Marceneiro', 'description' => 'Móveis sob medida, reparos em madeira e instalações.', 'slug' => 'marceneiro', 'icon' => 'fas fa-chair'],
            ['area' => $a, 'title' => 'Vidraceiro', 'description' => 'Envidraçamento, box e fechamentos em vidro temperado.', 'slug' => 'vidraceiro', 'icon' => 'fas fa-border-all'],
            ['area' => $a, 'title' => 'Impermeabilizador', 'description' => 'Tratamento de lajes, telhados e infiltrações.', 'slug' => 'impermeabilizador', 'icon' => 'fas fa-tint'],
            ['area' => $a, 'title' => 'Telhadista', 'description' => 'Manutenção e troca de telhas, calhas e rufos.', 'slug' => 'telhadista', 'icon' => 'fas fa-home'],
            ['area' => $a, 'title' => 'Demolidor leve', 'description' => 'Remoção de alvenaria leve, pisos antigos e preparação para reforma.', 'slug' => 'demolidor-leve', 'icon' => 'fas fa-tools'],
        ];
    }

    private function electricalProfessions(): array
    {
        $a = 'Instalações elétricas';

        return [
            ['area' => $a, 'title' => 'Eletricista residencial', 'description' => 'Quadros, disjuntores, tomadas e iluminação com segurança.', 'slug' => 'eletricista-residencial', 'icon' => 'fas fa-plug'],
            ['area' => $a, 'title' => 'Eletricista industrial', 'description' => 'Comandos, motores e manutenção em máquinas de pequeno porte.', 'slug' => 'eletricista-industrial', 'icon' => 'fas fa-industry'],
            ['area' => $a, 'title' => 'Instalador de ar-condicionado', 'description' => 'Split, infraestrutura elétrica dedicada e dreno.', 'slug' => 'instalador-ar-condicionado', 'icon' => 'fas fa-fan'],
            ['area' => $a, 'title' => 'Automação residencial', 'description' => 'Interruptores inteligentes, sensores e integração com assistentes.', 'slug' => 'automacao-residencial', 'icon' => 'fas fa-lightbulb'],
            ['area' => $a, 'title' => 'Instalador de energia solar', 'description' => 'String boxes, inversores e interconexão em projetos residenciais.', 'slug' => 'instalador-energia-solar', 'icon' => 'fas fa-solar-panel'],
            ['area' => $a, 'title' => 'Eletricista de painéis', 'description' => 'Montagem e manutenção de painéis e subestações de baixa tensão.', 'slug' => 'eletricista-paineis', 'icon' => 'fas fa-bolt'],
        ];
    }

    private function plumbingProfessions(): array
    {
        $a = 'Instalações hidráulicas';

        return [
            ['area' => $a, 'title' => 'Encanador', 'description' => 'Tubulações, registros, vasos sanitários e torneiras.', 'slug' => 'encanador', 'icon' => 'fas fa-wrench'],
            ['area' => $a, 'title' => 'Desentupidor', 'description' => 'Desobstrução de ralos, esgotos e colunas com equipamentos adequados.', 'slug' => 'desentupidor', 'icon' => 'fas fa-toilet'],
            ['area' => $a, 'title' => 'Instalador de aquecedor', 'description' => 'Aquecedores a gás e elétricos com ventilação e testes de estanqueidade.', 'slug' => 'instalador-aquecedor', 'icon' => 'fas fa-hot-tub'],
            ['area' => $a, 'title' => 'Bombeiro hidráulico', 'description' => 'Bombas d’água, pressurizadores e reservatórios.', 'slug' => 'bombeiro-hidraulico', 'icon' => 'fas fa-tint'],
            ['area' => $a, 'title' => 'Piscineiro', 'description' => 'Manutenção de piscinas, bombas, filtros e tratamento químico.', 'slug' => 'piscineiro', 'icon' => 'fas fa-swimming-pool'],
            ['area' => $a, 'title' => 'Instalador de irrigação', 'description' => 'Aspersores, gotejamento e automação para jardins e hortas.', 'slug' => 'instalador-irrigacao', 'icon' => 'fas fa-seedling'],
        ];
    }

    private function healthProfessions(): array
    {
        $a = 'Saúde e bem-estar';

        return [
            ['area' => $a, 'title' => 'Massagista', 'description' => 'Massagens relaxantes, desportivas e liberação miofascial.', 'slug' => 'massagista', 'icon' => 'fas fa-spa'],
            ['area' => $a, 'title' => 'Fisioterapeuta autônomo', 'description' => 'Atendimento domiciliar conforme registro profissional e prescrição.', 'slug' => 'fisioterapeuta-autonomo', 'icon' => 'fas fa-heartbeat'],
            ['area' => $a, 'title' => 'Nutricionista', 'description' => 'Orientação alimentar e planos personalizados com base em avaliação.', 'slug' => 'nutricionista', 'icon' => 'fas fa-apple-alt'],
            ['area' => $a, 'title' => 'Personal trainer', 'description' => 'Treinos personalizados ao ar livre, academia ou residência.', 'slug' => 'personal-trainer', 'icon' => 'fas fa-dumbbell'],
            ['area' => $a, 'title' => 'Técnico em enfermagem', 'description' => 'Curativos, medicações conforme prescrição e apoio a familiares.', 'slug' => 'tecnico-enfermagem', 'icon' => 'fas fa-user-md'],
            ['area' => $a, 'title' => 'Psicólogo', 'description' => 'Atendimento clínico presencial ou online conforme CRP.', 'slug' => 'psicologo', 'icon' => 'fas fa-brain'],
        ];
    }

    private function beautyProfessions(): array
    {
        $a = 'Beleza e cuidados pessoais';

        return [
            ['area' => $a, 'title' => 'Cabeleireira', 'description' => 'Cortes, coloração, tratamentos e penteados para eventos.', 'slug' => 'cabeleireira', 'icon' => 'fas fa-cut'],
            ['area' => $a, 'title' => 'Manicure e pedicure', 'description' => 'Unhas em gel, esmaltação e cuidados com pés e mãos.', 'slug' => 'manicure-pedicure', 'icon' => 'fas fa-hand-sparkles'],
            ['area' => $a, 'title' => 'Barbeiro', 'description' => 'Cortes masculinos, barba e tratamentos faciais básicos.', 'slug' => 'barbeiro', 'icon' => 'fas fa-cut'],
            ['area' => $a, 'title' => 'Maquiadora', 'description' => 'Maquiagem social, noiva e produção para fotos e vídeos.', 'slug' => 'maquiadora', 'icon' => 'fas fa-palette'],
            ['area' => $a, 'title' => 'Depiladora', 'description' => 'Depilação com cera e técnicas hígicas em estúdio ou domicílio.', 'slug' => 'depiladora', 'icon' => 'fas fa-feather-alt'],
            ['area' => $a, 'title' => 'Designer de sobrancelhas', 'description' => 'Design, henna e harmonização do olhar.', 'slug' => 'designer-sobrancelhas', 'icon' => 'fas fa-eye'],
            ['area' => $a, 'title' => 'Esteticista', 'description' => 'Limpeza de pele, drenagem e protocolos faciais.', 'slug' => 'esteticista', 'icon' => 'fas fa-smile-beam'],
        ];
    }

    private function automotiveProfessions(): array
    {
        $a = 'Automotivo e mecânica';

        return [
            ['area' => $a, 'title' => 'Mecânico automotivo', 'description' => 'Motor, suspensão, freios e diagnóstico em veículos leves.', 'slug' => 'mecanico-automotivo', 'icon' => 'fas fa-car'],
            ['area' => $a, 'title' => 'Eletricista automotivo', 'description' => 'Partida, alternador, injeção eletrônica e acessórios.', 'slug' => 'eletricista-automotivo', 'icon' => 'fas fa-car-battery'],
            ['area' => $a, 'title' => 'Funileiro e pintor automotivo', 'description' => 'Martelinho, massa e pintura localizada.', 'slug' => 'funileiro-pintor-automotivo', 'icon' => 'fas fa-spray-can'],
            ['area' => $a, 'title' => 'Lavador de veículos', 'description' => 'Lavagem, enceramento e higienização interna.', 'slug' => 'lavador-veiculos', 'icon' => 'fas fa-tint'],
            ['area' => $a, 'title' => 'Instalador de som automotivo', 'description' => 'Multimídia, amplificadores, falantes e cabeamento.', 'slug' => 'instalador-som-automotivo', 'icon' => 'fas fa-music'],
            ['area' => $a, 'title' => 'Borracheiro', 'description' => 'Montagem, balanceamento e reparo de pneus.', 'slug' => 'borracheiro', 'icon' => 'fas fa-life-ring'],
        ];
    }

    private function agricultureProfessions(): array
    {
        $a = 'Agronegócio e zona rural';

        return [
            ['area' => $a, 'title' => 'Tratorista', 'description' => 'Operação de tratores em preparo de solo, plantio e transporte rural.', 'slug' => 'tratorista', 'icon' => 'fas fa-tractor'],
            ['area' => $a, 'title' => 'Trabalhador rural', 'description' => 'Colheita manual, poda, capina e manejo de culturas.', 'slug' => 'trabalhador-rural', 'icon' => 'fas fa-leaf'],
            ['area' => $a, 'title' => 'Vaqueiro', 'description' => 'Manejo de rebanho, ordenha assistida e tratos diários.', 'slug' => 'vaqueiro', 'icon' => 'fas fa-cow'],
            ['area' => $a, 'title' => 'Operador de irrigação', 'description' => 'Pivôs, aspersão e manutenção de sistemas de irrigação.', 'slug' => 'operador-irrigacao', 'icon' => 'fas fa-tint'],
            ['area' => $a, 'title' => 'Aplicador de defensivos', 'description' => 'Pulverização com EPIs e registro conforme legislação vigente.', 'slug' => 'aplicador-defensivos', 'icon' => 'fas fa-flask'],
            ['area' => $a, 'title' => 'Classificador de grãos', 'description' => 'Triagem, armazenagem e amostragem em propriedades e cooperativas.', 'slug' => 'classificador-graos', 'icon' => 'fas fa-seedling'],
        ];
    }

    private function educationProfessions(): array
    {
        $a = 'Educação e aulas particulares';

        return [
            ['area' => $a, 'title' => 'Professor de reforço escolar', 'description' => 'Matemática, português e ciências para ensinos fundamental e médio.', 'slug' => 'reforco-escolar', 'icon' => 'fas fa-book'],
            ['area' => $a, 'title' => 'Professor de inglês', 'description' => 'Conversação, gramática e preparação para viagens e exames.', 'slug' => 'professor-ingles', 'icon' => 'fas fa-language'],
            ['area' => $a, 'title' => 'Professor de violão', 'description' => 'Aulas para iniciantes e intermediários com repertório popular.', 'slug' => 'professor-violao', 'icon' => 'fas fa-guitar'],
            ['area' => $a, 'title' => 'Professor de informática básica', 'description' => 'Windows, internet, e-mail e ferramentas do dia a dia.', 'slug' => 'professor-informatica-basica', 'icon' => 'fas fa-keyboard'],
            ['area' => $a, 'title' => 'Instrutor de natação', 'description' => 'Aulas em clubes ou residências com piscina, conforme certificação.', 'slug' => 'instrutor-natacao', 'icon' => 'fas fa-swimmer'],
            ['area' => $a, 'title' => 'Coach de estudos', 'description' => 'Planejamento de rotina, técnicas de leitura e organização acadêmica.', 'slug' => 'coach-estudos', 'icon' => 'fas fa-graduation-cap'],
        ];
    }

    private function logisticsProfessions(): array
    {
        $a = 'Logística e transporte';

        return [
            ['area' => $a, 'title' => 'Motorista particular', 'description' => 'Deslocamentos urbanos e viagens com veículo do cliente ou condutor.', 'slug' => 'motorista-particular', 'icon' => 'fas fa-car-side'],
            ['area' => $a, 'title' => 'Motorista de entregas', 'description' => 'Motoboy e carro para entregas rápidas e e-commerce local.', 'slug' => 'motorista-entregas', 'icon' => 'fas fa-shipping-fast'],
            ['area' => $a, 'title' => 'Carreteiro', 'description' => 'Transporte de cargas leves e médias com veículo próprio.', 'slug' => 'carreteiro', 'icon' => 'fas fa-truck-moving'],
            ['area' => $a, 'title' => 'Ajudante de carga e descarga', 'description' => 'Apoio em mudanças, armazéns e eventos.', 'slug' => 'ajudante-carga-descarga', 'icon' => 'fas fa-dolly-flatbed'],
            ['area' => $a, 'title' => 'Motofretista', 'description' => 'Documentos, pequenas encomendas e serviços urgentes.', 'slug' => 'motofretista', 'icon' => 'fas fa-motorcycle'],
            ['area' => $a, 'title' => 'Operador de empilhadeira', 'description' => 'Movimentação de paletes em depósitos com CNH e curso válidos.', 'slug' => 'operador-empilhadeira', 'icon' => 'fas fa-warehouse'],
        ];
    }

    private function adminProfessions(): array
    {
        $a = 'Administrativo e escritório';

        return [
            ['area' => $a, 'title' => 'Assistente administrativo', 'description' => 'Agenda, e-mails, planilhas e apoio a gestores.', 'slug' => 'assistente-administrativo', 'icon' => 'fas fa-briefcase'],
            ['area' => $a, 'title' => 'Digitador', 'description' => 'Lançamentos, formulários e conferência de dados.', 'slug' => 'digitador', 'icon' => 'fas fa-keyboard'],
            ['area' => $a, 'title' => 'Recepcionista', 'description' => 'Atendimento presencial e telefônico, cadastros e encaminhamentos.', 'slug' => 'recepcionista', 'icon' => 'fas fa-phone'],
            ['area' => $a, 'title' => 'Auxiliar de contas a pagar', 'description' => 'Conferência de notas, boletos e conciliações básicas.', 'slug' => 'auxiliar-contas-pagar', 'icon' => 'fas fa-file-invoice-dollar'],
            ['area' => $a, 'title' => 'Arquivista', 'description' => 'Organização física e digital de documentos e protocolos.', 'slug' => 'arquivista', 'icon' => 'fas fa-archive'],
            ['area' => $a, 'title' => 'Telemarketing', 'description' => 'Prospecção, suporte nível 1 e pesquisas por telefone.', 'slug' => 'telemarketing', 'icon' => 'fas fa-headset'],
        ];
    }

    private function hospitalityProfessions(): array
    {
        $a = 'Hotelaria e turismo';

        return [
            ['area' => $a, 'title' => 'Recepcionista hoteleira', 'description' => 'Check-in, reservas e orientação a hóspedes.', 'slug' => 'recepcionista-hoteleira', 'icon' => 'fas fa-concierge-bell'],
            ['area' => $a, 'title' => 'Camareira de hotel', 'description' => 'Higienização de apartamentos e reposição de amenities.', 'slug' => 'camareira-hotel', 'icon' => 'fas fa-bed'],
            ['area' => $a, 'title' => 'Governanta', 'description' => 'Escalas, inspeção de quartos e padronização de limpeza.', 'slug' => 'governanta', 'icon' => 'fas fa-clipboard-check'],
            ['area' => $a, 'title' => 'Guia de turismo local', 'description' => 'Roteiros em Batatais e região com cadastro no órgão competente.', 'slug' => 'guia-turismo-local', 'icon' => 'fas fa-map-marked-alt'],
            ['area' => $a, 'title' => 'Concierge', 'description' => 'Reservas, recomendações e apoio personalizado a hóspedes.', 'slug' => 'concierge', 'icon' => 'fas fa-hotel'],
        ];
    }

    private function eventsProfessions(): array
    {
        $a = 'Eventos e entretenimento';

        return [
            ['area' => $a, 'title' => 'Montador de estruturas para eventos', 'description' => 'Tendas, palcos, cenografia leve e desmontagem.', 'slug' => 'montador-estruturas-eventos', 'icon' => 'fas fa-campground'],
            ['area' => $a, 'title' => 'Técnico de som', 'description' => 'PA, microfones e mixagem para palestras e shows pequenos.', 'slug' => 'tecnico-som', 'icon' => 'fas fa-sliders-h'],
            ['area' => $a, 'title' => 'Técnico de iluminação', 'description' => 'Plotagem básica, refletores e efeitos para festas.', 'slug' => 'tecnico-iluminacao', 'icon' => 'fas fa-lightbulb'],
            ['area' => $a, 'title' => 'Recepcionista de eventos', 'description' => 'Credenciamento, entrega de kits e orientação ao público.', 'slug' => 'recepcionista-eventos', 'icon' => 'fas fa-id-card'],
            ['area' => $a, 'title' => 'Auxiliar de montagem de eventos', 'description' => 'Apoio em montagem de cadeiras, mesas e kit de coffee break.', 'slug' => 'auxiliar-montagem-eventos', 'icon' => 'fas fa-people-carry'],
        ];
    }

    private function petsProfessions(): array
    {
        $a = 'Pets e animais';

        return [
            ['area' => $a, 'title' => 'Dog walker', 'description' => 'Passeios com cães com segurança e socialização controlada.', 'slug' => 'dog-walker', 'icon' => 'fas fa-dog'],
            ['area' => $a, 'title' => 'Pet sitter', 'description' => 'Cuidados em domicílio durante viagens do tutor.', 'slug' => 'pet-sitter', 'icon' => 'fas fa-paw'],
            ['area' => $a, 'title' => 'Tosador', 'description' => 'Banho, tosa higiênica e estética para cães e gatos.', 'slug' => 'tosador', 'icon' => 'fas fa-cut'],
            ['area' => $a, 'title' => 'Adestrador de cães', 'description' => 'Comandos básicos, manejo de guia e socialização.', 'slug' => 'adestrador-caes', 'icon' => 'fas fa-bone'],
            ['area' => $a, 'title' => 'Cuidador de pets em creche', 'description' => 'Supervisão em grupos pequenos com brincadeiras e alimentação.', 'slug' => 'cuidador-pets-creche', 'icon' => 'fas fa-cat'],
        ];
    }

    private function gardenProfessions(): array
    {
        $a = 'Jardinagem e paisagismo';

        return [
            ['area' => $a, 'title' => 'Jardineiro', 'description' => 'Poda, capina, adubação e manutenção de jardins residenciais.', 'slug' => 'jardineiro', 'icon' => 'fas fa-leaf'],
            ['area' => $a, 'title' => 'Paisagista', 'description' => 'Projetos leves, escolha de espécies e composição de áreas verdes.', 'slug' => 'paisagista', 'icon' => 'fas fa-tree'],
            ['area' => $a, 'title' => 'Rocadeirista', 'description' => 'Roçada de terrenos, margens de estradas e lotes.', 'slug' => 'rocadeirista', 'icon' => 'fas fa-seedling'],
            ['area' => $a, 'title' => 'Cuidador de horta', 'description' => 'Plantio, controle de pragas orgânico e colheita doméstica.', 'slug' => 'cuidador-horta', 'icon' => 'fas fa-carrot'],
        ];
    }

    private function creativeProfessions(): array
    {
        $a = 'Arte, comunicação e mídia';

        return [
            ['area' => $a, 'title' => 'Designer gráfico', 'description' => 'Identidade visual, artes para redes e impressos.', 'slug' => 'designer-grafico', 'icon' => 'fas fa-pen-nib'],
            ['area' => $a, 'title' => 'Fotógrafo', 'description' => 'Ensaios, eventos e produtos com edição básica.', 'slug' => 'fotografo', 'icon' => 'fas fa-camera'],
            ['area' => $a, 'title' => 'Videomaker', 'description' => 'Captação e edição de vídeos para marcas e perfis sociais.', 'slug' => 'videomaker', 'icon' => 'fas fa-video'],
            ['area' => $a, 'title' => 'Redator', 'description' => 'Textos para sites, blogs e anúncios com foco em conversão.', 'slug' => 'redator', 'icon' => 'fas fa-pen'],
            ['area' => $a, 'title' => 'Social media', 'description' => 'Planejamento de conteúdo, posts e métricas básicas.', 'slug' => 'social-media', 'icon' => 'fas fa-hashtag'],
        ];
    }

    private function metalProfessions(): array
    {
        $a = 'Metalurgia e solda';

        return [
            ['area' => $a, 'title' => 'Soldador', 'description' => 'Processos MIG, TIG e eletrodo em chapas e estruturas leves.', 'slug' => 'soldador', 'icon' => 'fas fa-fire'],
            ['area' => $a, 'title' => 'Serralheiro', 'description' => 'Grades, portões, janelas e estruturas metálicas sob medida.', 'slug' => 'serralheiro', 'icon' => 'fas fa-border-style'],
            ['area' => $a, 'title' => 'Caldeireiro', 'description' => 'Dobras, tanques e peças em chapa para indústrias leves.', 'slug' => 'caldeireiro', 'icon' => 'fas fa-tools'],
            ['area' => $a, 'title' => 'Operador de plasma', 'description' => 'Corte CNC e acabamento em chapas para projetos metálicos.', 'slug' => 'operador-plasma', 'icon' => 'fas fa-cut'],
        ];
    }

    private function movingProfessions(): array
    {
        $a = 'Mudanças e montagem';

        return [
            ['area' => $a, 'title' => 'Montador de móveis', 'description' => 'Montagem de módulos planejados, racks e móveis de loja.', 'slug' => 'montador-moveis', 'icon' => 'fas fa-couch'],
            ['area' => $a, 'title' => 'Motorista de mudanças', 'description' => 'Transporte residencial com equipe de carregamento.', 'slug' => 'motorista-mudancas', 'icon' => 'fas fa-truck-loading'],
            ['area' => $a, 'title' => 'Embalador profissional', 'description' => 'Embalagens, proteção de vidros e rotulagem de caixas.', 'slug' => 'embalador-profissional', 'icon' => 'fas fa-box-open'],
            ['area' => $a, 'title' => 'Desmontador de móveis', 'description' => 'Desmontagem segura para mudanças e transporte.', 'slug' => 'desmontador-moveis', 'icon' => 'fas fa-screwdriver'],
        ];
    }

    private function globalListingCard(): array
    {
        $a = 'Serviços gerais e portal';

        return [
            [
                'area' => $a,
                'title' => 'Outros Serviços',
                'description' => 'Explore outras categorias de profissionais disponíveis em Batatais.',
                'slug' => 'explorar-todos-profissionais',
                'icon' => 'fas fa-star',
                'show_on_homepage' => true,
                'is_global_listing' => true,
            ],
        ];
    }
}
