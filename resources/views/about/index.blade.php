@extends('layouts.guest')

@section('title', __('labels.about').' | '.config('app.name'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')
    <section class="about-hero">
        <div class="container">
            <p class="about-kicker">{{ __('labels.about') }}</p>
            <h1>Sobre a SpiderSoft</h1>
            <p class="lead mb-0">Tecnologia que nasce para transformar ideias em realidade.</p>
        </div>
    </section>

    <section class="about-content">
        <div class="container">
            <article class="about-article">
                <p>
                    A <strong>SpiderSoft</strong> é um coletivo de tecnologia criado com um propósito simples: desenvolver soluções digitais capazes de resolver problemas reais, aproximar pessoas e transformar boas ideias em produtos de verdade.
                </p>

                <p>
                    Nossa história começa em <strong>Batatais, São Paulo</strong>, com o desenvolvimento do <strong>Batatais Serviços</strong>, nosso primeiro produto. A plataforma nasceu de uma necessidade local: tornar mais simples a tarefa de encontrar profissionais e contratar serviços com praticidade, reunindo em um único ambiente pessoas que precisam de um serviço e profissionais que desejam divulgar seu trabalho.
                </p>

                <p>
                    O Batatais Serviços foi pensado como um marketplace local, permitindo que profissionais apresentem suas áreas de atuação, serviços, valores, disponibilidade e informações relevantes, enquanto contratantes podem encontrar e conhecer profissionais de maneira mais organizada. A plataforma também conta com recursos como avaliações, histórico de serviços, cadastro profissional, documentos e processo de verificação.
                </p>

                <p>
                    Mais do que desenvolver uma plataforma, o projeto representa o primeiro passo da SpiderSoft na construção de produtos próprios. Nossa intenção é criar tecnologia com propósito: soluções que sejam úteis para as pessoas, sustentáveis como negócio e preparadas para evoluir junto às necessidades de seus usuários.
                </p>

                <h2>Quem está por trás</h2>

                <p>
                    A SpiderSoft tem como desenvolvedor principal <strong>Eric Isaias Tomasini</strong>, profissional da área de tecnologia com atuação em desenvolvimento Full Stack, arquitetura de software e construção de aplicações web.
                </p>

                <p>
                    Sua trajetória reúne formação em <strong>Análise e Desenvolvimento de Sistemas</strong>, formação técnica em <strong>Desenvolvimento de Sistemas</strong> e pós-graduação em <strong>Desenvolvimento Web</strong>, além de experiência profissional no desenvolvimento e sustentação de sistemas utilizando tecnologias como PHP, Laravel, Vue.js, APIs RESTful, bancos de dados relacionais, Docker e Git.
                </p>

                <p>
                    Ao longo de sua carreira, Eric também participou de projetos envolvendo sistemas de larga utilização, integrações com APIs, microsserviços, processamento de dados e soluções de IoT, sempre buscando aliar engenharia de software, organização e inovação à resolução de problemas concretos.
                </p>

                <blockquote class="about-callout">
                    <p>
                        Essa experiência serve como base para a filosofia da SpiderSoft: <strong>não desenvolver tecnologia apenas por desenvolver, mas utilizar tecnologia como ferramenta para criar soluções que façam sentido no mundo real.</strong>
                    </p>
                </blockquote>

                <h2>Nosso primeiro passo</h2>

                <p>
                    O <strong>Batatais Serviços</strong> representa justamente essa visão.
                </p>

                <p>
                    Começamos localmente, conhecendo uma necessidade próxima e construindo uma solução para ela. A partir desse primeiro produto, a SpiderSoft busca crescer, experimentar novas ideias e desenvolver outras soluções que possam gerar valor para pessoas, profissionais e empresas.
                </p>

                <p>
                    Acreditamos que grandes produtos não precisam começar grandes. Eles precisam começar com uma boa ideia, um problema verdadeiro para resolver e a disposição de construir algo melhor.
                </p>

                <div class="about-motto">
                    <p>SpiderSoft — Com grandes tecnologias, vêm grandes inovações.</p>
                </div>
            </article>
        </div>
    </section>
@endsection
