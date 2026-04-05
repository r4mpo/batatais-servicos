<?php

/*
|--------------------------------------------------------------------------
| Arquivo de Traduções das Labels - Português (Brasil)
|--------------------------------------------------------------------------
|
| Este arquivo contém todas as strings de texto utilizadas nas labels da aplicação,
| organizadas por seções para facilitar manutenção e reutilização.
|
| Estrutura recomendada:
| - Navegação básica / botões genéricos
| - Área Segura (Confirmação de senha)
| - Recursos de segurança (features)
| - Login
| - Registro / Criar Conta
| - Recuperação de senha
| - Redefinição de senha
| - Verificação de e-mail
| - Landing Page / Home
| - Estatísticas da landing
| - Seção "Por que escolher"
| - Call To Action (CTA)
| - Navbar
| - Footer
| - Modais
|
| Regras e boas práticas:
| 1. Manter índices únicos para cada string.
| 2. Utilizar nomes descritivos e consistentes.
| 3. Agrupar strings similares (ex.: placeholders, labels).
| 4. Adicionar novas chaves caso algum texto esteja direto no Blade.
| 5. Evitar textos duplicados sem necessidade.
|
| Exemplo de uso no Blade:
|     {{ __('labels.back') }}
|     {{ __('labels.login_button') }}
|
| Para seções de features ou modais, manter subarrays quando necessário.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Navegação básica / botões genéricos
    |--------------------------------------------------------------------------
    */

    'back' => 'Voltar',
    'or' => 'ou',
    'and' => 'e',

    'login' => 'Fazer Login',
    'logout' => 'Sair',
    'logout_account' => 'Sair da conta',

    'register' => 'Cadastre-se',

    'back_to_home' => 'Voltar para a',
    'home_page' => 'página inicial',
    'back_home' => 'Voltar para a',
    'back_to_site' => 'Voltar ao site',

    'understood' => 'Entendi',

    /*
    |--------------------------------------------------------------------------
    | Área Segura (Confirmação de senha)
    |--------------------------------------------------------------------------
    */

    'secure_area' => 'Área Segura',

    'confirm_password_title' => 'Confirmar Senha',
    'confirm_password_subtitle' => 'Digite sua senha para continuar',

    'confirm_password_description' =>
        'Para continuar, confirme sua senha. Isso ajuda a proteger sua conta e seus dados.',

    'password_label' => 'Senha',
    'password_placeholder' => 'Digite sua senha',

    'confirm_password_button' => 'Confirmar Senha',

    /*
    |--------------------------------------------------------------------------
    | Recursos de segurança (features usadas em telas auth)
    |--------------------------------------------------------------------------
    */

    'features' => [
        'data_protection' => 'Proteção de dados',
        'secure_environment' => 'Ambiente seguro',
        'identity_verification' => 'Verificação de identidade',
    ],

    'feature_secure_access' => 'Acesso seguro e protegido',
    'feature_secure_data' => 'Dados seguros e protegidos',
    'feature_secure_process' => 'Processo seguro',
    'feature_security_protection' => 'Proteção contra acessos indevidos',

    'feature_fast' => 'Comece em segundos',
    'feature_start_fast' => 'Comece em segundos',
    'feature_fast_verification' => 'Verificação rápida e segura',

    'feature_community' => 'Conecte com a comunidade',
    'feature_email_link' => 'Link enviado por e-mail',

    'feature_activity_history' => 'Histórico completo de atividades',
    'feature_account_protection' => 'Proteção da sua conta',

    'feature_password_protected' => 'Senha protegida',
    'feature_access_restored' => 'Acesso restaurado',

    'feature_notifications' => 'Notificações em tempo real',
    'feature_support' => 'Suporte dedicado',

    'feature_verification' => 'Verificação simples',
    'feature_simple_verification' => 'Verificação simples',

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    'welcome_back_title' => 'Bem-vindo de Volta!',
    'welcome_back_description' =>
        'Acesse sua conta e continue conectado com oportunidades',

    'login_title' => 'Entrar',
    'login_description' => 'Entre em sua conta',

    'remember_me' => 'Lembrar-me nesta máquina',
    'login_button' => 'Entrar',

    'no_account' => 'Não tem conta?',
    'forgot_password' => 'Esqueceu sua senha?',

    /*
    |--------------------------------------------------------------------------
    | Registro (Criar conta)
    |--------------------------------------------------------------------------
    */

    'join_title' => 'Junte-se a Nós!',
    'join_subtitle' =>
        'Crie sua conta e comece a conectar com oportunidades',

    'create_account_title' => 'Criar Conta',
    'create_account_description' => 'Preencha seus dados abaixo',

    'create_account' => 'Criar Conta',
    'create_account_button' => 'Criar Conta',

    'fill_data' => 'Preencha seus dados abaixo',

    'profile_question' => 'Como você deseja se cadastrar?',
    'profile_contractor' => 'Contratante',
    'profile_professional' => 'Profissional',

    'contractor' => 'Contratante',
    'professional' => 'Profissional',

    'full_name' => 'Nome Completo',
    'full_name_placeholder' => 'Seu nome',

    'name_placeholder' => 'Seu nome',

    'email' => 'E-mail',
    'email_label' => 'E-mail',
    'email_placeholder' => 'Digite seu e-mail',

    'password' => 'Senha',
    'password_confirmation' => 'Confirmar Senha',
    'password_confirm' => 'Confirmar Senha',

    'password_confirmation_placeholder' => 'Confirme a senha',
    'password_confirm_placeholder' => 'Confirme a senha',

    'agree_terms' => 'Concordo com os',
    'accept_terms' => 'Concordo com os',

    'terms_of_use' => 'Termos de Uso',
    'privacy_policy' => 'Política de Privacidade',

    'already_have_account' => 'Já tem conta?',
    'already_account' => 'Já tem conta?',

/*
|--------------------------------------------------------------------------
| Modais
|--------------------------------------------------------------------------
|
| Traduções dos modais de Termos de Uso e Política de Privacidade.
|
*/

'terms_modal_title' => 'Termos de Uso - Batatais Serviços',
'terms_modal_body' => 'Bem-vindo à plataforma ' . config('app.name') . '. Ao criar uma conta, você concorda em utilizar o sistema de forma responsável e respeitosa com todos os usuários da comunidade.

As informações fornecidas durante o cadastro, como **nome, telefone, endereço, valores de serviços, histórico de interações e demais dados**, são armazenadas com segurança em nosso banco de dados.

Esses dados podem ser utilizados dentro da plataforma exclusivamente com o objetivo de **melhorar a experiência de uso**, facilitar a comunicação entre usuários e permitir que profissionais e contratantes possam interagir de forma eficiente.

Informações como **endereços, telefones e valores financeiros** podem ser compartilhadas entre usuários apenas quando necessário para a realização de serviços, negociações ou contato entre as partes envolvidas na plataforma.

A plataforma ' . config('app.name') . ' tem como objetivo principal promover a conexão entre profissionais e contratantes da cidade de **Batatais - SP**, incentivando a colaboração, o desenvolvimento local e a facilitação de serviços dentro da comunidade.

Todo o fluxo de utilização do sistema foi pensado para proporcionar uma experiência segura, prática e eficiente para os habitantes de Batatais e região.

Ao continuar utilizando a plataforma, você declara estar de acordo com estes termos e com o uso das informações conforme descrito acima.',

'privacy_modal_title' => 'Política de Privacidade - Batatais Serviços',
'privacy_modal_body' => 'A ' . config('app.name') . ' respeita a privacidade de seus usuários e se compromete a proteger todas as informações fornecidas durante o uso da plataforma.

Durante o cadastro e utilização do sistema, algumas informações podem ser coletadas, como **nome, e-mail, telefone, endereço, histórico de interações e dados relacionados a serviços realizados ou solicitados**.

Essas informações são armazenadas de forma segura em nossos sistemas e utilizadas exclusivamente para permitir o funcionamento da plataforma, melhorar a experiência dos usuários e facilitar a comunicação entre profissionais e contratantes.

Dados como **telefone, endereço ou valores de serviços** podem ser compartilhados entre usuários da plataforma quando necessário para a realização de serviços, negociações ou contato direto entre as partes envolvidas.

A plataforma não comercializa ou distribui informações pessoais para terceiros fora do contexto de funcionamento do sistema.

Nosso objetivo é oferecer um ambiente seguro e confiável para a conexão entre profissionais e contratantes da cidade de **Batatais - SP**, promovendo a colaboração entre os moradores e facilitando a contratação de serviços locais.

Ao utilizar a plataforma, você concorda com o armazenamento e utilização das informações conforme descrito nesta política de privacidade.

Caso tenha dúvidas sobre como seus dados são utilizados, recomendamos entrar em contato com os administradores da plataforma.',

'understood' => 'Entendi',    /*
    |--------------------------------------------------------------------------
    | Recuperação de senha
    |--------------------------------------------------------------------------
    */

    'recover_password_title' => 'Recuperar Senha',

    'recover_password_description' =>
        'Esqueceu sua senha? Informe seu e-mail e enviaremos um link para redefinição.',

    'form_title' => 'Recuperar senha',
    'form_description' => 'Digite seu e-mail para receber o link',

    'send_reset_link' => 'Enviar link de redefinição',

    /*
    |--------------------------------------------------------------------------
    | Redefinição de senha
    |--------------------------------------------------------------------------
    */

    'reset_password_title' => 'Redefinir senha',

    'reset_password_description' =>
        'Crie uma nova senha para acessar sua conta com segurança.',

    'new_password_title' => 'Nova senha',
    'new_password_description' => 'Digite sua nova senha abaixo',

    'new_password_label' => 'Nova senha',
    'new_password_placeholder' => 'Digite a nova senha',

    'confirm_password_label' => 'Confirmar senha',
    'confirm_password_placeholder' => 'Confirme a senha',

    'reset_password_button' => 'Redefinir senha',

    /*
    |--------------------------------------------------------------------------
    | Verificação de e-mail
    |--------------------------------------------------------------------------
    */

    'confirm_email_title' => 'Confirme seu Email',

    'confirm_email_description' =>
        'Para garantir a segurança da sua conta, precisamos confirmar seu endereço de email antes de continuar.',

    'email_verification_title' => 'Verificação de Email',
    'email_verification_description' => 'Confirme seu endereço para continuar',

    'check_inbox_message' =>
        'Verifique sua caixa de entrada e clique no link enviado para confirmar seu email.',

    'verification_link_sent' =>
        'Um novo link de verificação foi enviado para seu email.',

    'resend_verification_email' => 'Reenviar Email de Verificação',

    'feature_activate_account' => 'Ative sua conta em poucos segundos',

    /*
    |--------------------------------------------------------------------------
    | Landing Page / Home
    |--------------------------------------------------------------------------
    */

    'hero_title_part1' => 'Encontre Profissionais',
    'hero_title_highlight' => 'de Confiança',
    'hero_title_part2' => 'em Batatais',

    'hero_description' =>
        'Conectamos você com os melhores profissionais da cidade.',

    'hero_contractor' => 'Sou Contratante',
    'hero_professional' => 'Sou Profissional',

    /*
    |--------------------------------------------------------------------------
    | Categorias de profissionais (home / serviços)
    |--------------------------------------------------------------------------
    */

    'professional_categories_title' => 'Categorias de Profissionais',
    'professional_categories_view_all' => 'Ver Todos',
    'professional_categories_view_professionals' => 'Ver Profissionais',
    'professional_categories_empty' => 'Nenhuma categoria disponível no momento.',

    /*
    |--------------------------------------------------------------------------
    | Listagem de profissionais
    |--------------------------------------------------------------------------
    */

    'professionals_page_title' => 'Profissionais',
    'professionals_filters_heading' => 'Filtros',
    'professionals_search_label' => 'Busca',
    'professionals_search_placeholder' => 'Buscar profissional...',
    'professionals_sort_heading' => 'Ordenar por',
    'professionals_sort_relevance' => 'Mais relevante',
    'professionals_sort_rating' => 'Melhor avaliado',
    'professionals_sort_price_asc' => 'Menor preço (hora)',
    'professionals_sort_price_desc' => 'Maior preço (hora)',
    'professionals_sort_recent' => 'Mais recente',
    'professionals_category_heading' => 'Categoria',
    'professionals_category_select_placeholder' => 'Pesquisar e selecionar categorias…',
    'professionals_category_select_hint' => 'Use a caixa acima para filtrar a lista e selecionar uma ou mais categorias.',
    'professionals_rating_heading' => 'Avaliação',
    'professionals_rating_5' => '5 estrelas',
    'professionals_rating_4_plus' => '4+ estrelas',
    'professionals_price_heading' => 'Faixa de preço (por hora)',
    'professionals_price_up_to' => 'Até',
    'professionals_availability_heading' => 'Disponibilidade',
    'professionals_avail_today' => 'Disponível hoje',
    'professionals_avail_week' => 'Disponível nesta semana',
    'professionals_avail_24h' => 'Disponível 24h',
    'professionals_apply_filters' => 'Aplicar filtros',
    'professionals_clear_filters' => 'Limpar filtros',
    'professionals_showing' => 'Mostrando',
    'professionals_total_label' => 'profissionais',
    'professionals_empty' => 'Nenhum profissional encontrado com os filtros selecionados.',
    'professionals_reviews' => ':count avaliações',
    'professionals_per_hour' => 'hora',
    'professionals_view_profile' => 'Ver perfil',
    'professionals_contact' => 'Contato',
    'professionals_pagination_label' => 'Paginação da listagem',
    'professionals_rating_stars_label' => 'Nota :n de 5',
    'nav_professionals' => 'Profissionais',

    /*
    |--------------------------------------------------------------------------
    | Cadastro completo do profissional
    |--------------------------------------------------------------------------
    */

    'professional_onboarding_title' => 'Complete seu cadastro profissional',
    'professional_onboarding_subtitle' => 'Informe seus dados, documentos e como você aparece na plataforma. Você também pode alterar a senha nesta etapa.',
    'professional_onboarding_profession' => 'Categoria profissional',
    'professional_onboarding_profession_placeholder' => 'Selecione…',
    'professional_onboarding_profession_hint' => 'Escolha a categoria que melhor descreve o serviço que você presta. Use a busca ao abrir o campo para filtrar pelo nome.',
    'professional_onboarding_description_counter' => ':current / :max caracteres',
    'professional_onboarding_documents_section' => 'Documentos e identificação',
    'professional_onboarding_service_section' => 'Anúncio e valor do serviço',
    'professional_onboarding_rg' => 'RG',
    'professional_onboarding_rg_placeholder' => 'Ex.: MG-12.345.678-9',
    'professional_onboarding_rg_hint' => 'Número do documento de identidade (aceita letras, números e pontuação).',
    'professional_onboarding_cpf' => 'CPF',
    'professional_onboarding_cpf_placeholder' => '000.000.000-00',
    'professional_onboarding_cpf_hint' => 'Digite apenas os números; a máscara é aplicada automaticamente.',
    'professional_onboarding_cnpj' => 'CNPJ (opcional)',
    'professional_onboarding_cnpj_placeholder' => '00.000.000/0001-00',
    'professional_onboarding_cnpj_hint' => 'Preencha apenas se atuar como pessoa jurídica.',
    'professional_onboarding_title_field' => 'Título do anúncio',
    'professional_onboarding_title_placeholder' => 'Ex.: Encanador — atendimento em Batatais',
    'professional_onboarding_title_hint' => 'Uma frase curta que identifica o que você oferece.',
    'professional_onboarding_description' => 'Descrição',
    'professional_onboarding_description_placeholder' => 'Experiência, regiões de atendimento, disponibilidade e diferenciais.',
    'professional_onboarding_description_hint' => 'Opcional. Até 5.000 caracteres.',
    'professional_onboarding_hourly_rate' => 'Valor por hora (R$)',
    'professional_onboarding_hourly_placeholder' => '0,00',
    'professional_onboarding_hourly_hint' => 'Entre R$ 1,00 e R$ 500,00.',
    'professional_onboarding_password_section' => 'Alterar senha (opcional)',
    'professional_onboarding_password' => 'Nova senha',
    'professional_onboarding_password_confirm' => 'Confirmar nova senha',
    'professional_onboarding_password_confirm_placeholder' => 'Repita a nova senha',
    'professional_onboarding_password_placeholder' => 'Deixe em branco para manter a senha atual',
    'professional_onboarding_password_hint' => 'Se alterar, use uma senha forte (regras abaixo do campo em caso de erro).',
    'professional_onboarding_submit' => 'Salvar e continuar',
    'professional_onboarding_success' => 'Cadastro profissional concluído. Bem-vindo à sua área!',
    'professional_profile_updated_success' => 'Seus dados profissionais foram atualizados.',

    /*
    |--------------------------------------------------------------------------
    | Estatísticas da landing
    |--------------------------------------------------------------------------
    */

    'stats_professionals' => 'Profissionais Cadastrados',
    'stats_services' => 'Serviços Realizados',
    'stats_rating' => 'Avaliação Média',

    /*
    |--------------------------------------------------------------------------
    | Seção "Por que escolher"
    |--------------------------------------------------------------------------
    */

    'about_title_prefix' => 'Por que Escolher',
    'about_title_brand' => 'Batatais Serviços',

    'verified_professionals_title' => 'Profissionais Verificados',
    'verified_professionals_description' =>
        'Todos os profissionais são analisados e verificados para sua segurança.',

    'security_title' => 'Segurança Garantida',
    'security_description' =>
        'Plataforma segura com dados criptografados e confiável.',

    'communication_title' => 'Fácil Comunicação',
    'communication_description' =>
        'Chat integrado para comunicação direta com profissionais.',

    'history_title' => 'Histórico Completo',
    'history_description' =>
        'Acompanhe todos os seus serviços e contratações em um só lugar.',

    'support_title' => 'Suporte Local',
    'support_description' =>
        'Atendimento dedicado e responsivo para a comunidade de Batatais.',

    /*
    |--------------------------------------------------------------------------
    | Call To Action
    |--------------------------------------------------------------------------
    */

    'cta_title' => 'Comece Agora Mesmo!',
    'cta_description' =>
        'Encontre o profissional perfeito para suas necessidades',

    'cta_contractor' => 'Sou Contratante',
    'cta_professional' => 'Sou Profissional',

    /*
    |--------------------------------------------------------------------------
    | Navbar
    |--------------------------------------------------------------------------
    */

    'brand' => config('app.name'),

    'services' => 'Serviços',
    'user_area' => 'Área do Usuário',

    /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */

    'title' => config('app.name'),

    'description' =>
        'Conectando profissionais e contratantes em Batatais-SP com confiança e qualidade.',

    'quick_links' => 'Links Rápidos',

    'about' => 'Sobre Nós',
    'terms' => 'Termos de Uso',
    'privacy' => 'Privacidade',
    'contact' => 'Contato',

    'contact_title' => 'Contato',

    'copyright' => 'Todos os direitos reservados.',

    /*
    |--------------------------------------------------------------------------
    | Modais
    |--------------------------------------------------------------------------
    */

    'terms_modal_title' => 'Termos de Uso - Batatais Serviços',
    'privacy_modal_title' => 'Política de Privacidade - Batatais Serviços',

];
