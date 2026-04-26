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
    | Dashboard (área logada)
    |--------------------------------------------------------------------------
    */

    'dashboard_title' => 'Painel',
    'dashboard_alert_dismiss' => 'Fechar',

    'dashboard_contractor_title' => 'Bem-vindo à sua área',
    'dashboard_contractor_lead' => 'Você está conectado. Use o menu para navegar pelo site ou acesse seu perfil para atualizar dados da conta.',
    'dashboard_contractor_profile_link' => 'Ir para meu perfil',

    'dashboard_professional_lead' => 'Central do profissional: gerencie seu cadastro, acompanhe indicadores e prepare-se para novas funções da plataforma.',
    'dashboard_finance_title' => 'Resumo financeiro',
    'dashboard_finance_disclaimer' => 'Valores ilustrativos — em breve você verá aqui seus ganhos e saques reais.',
    'dashboard_finance_available_withdrawal' => 'Disponível para saque',
    'dashboard_finance_net_available' => 'Líquido disponível',
    'dashboard_finance_total_withdrawn' => 'Total sacado',
    'dashboard_finance_net_withdrawn' => 'Líquido sacado',
    'dashboard_finance_sample_available' => 'R$ 1.250,00',
    'dashboard_finance_sample_net_available' => 'R$ 1.125,00',
    'dashboard_finance_sample_total_withdrawn' => 'R$ 8.500,00',
    'dashboard_finance_sample_net_withdrawn' => 'R$ 7.650,00',

    'dashboard_card_professional_title' => 'Cadastro profissional',
    'dashboard_card_professional_text' => 'Atualize categoria, documentos (RG, CPF, CNPJ), título do anúncio, descrição e valor por hora exibidos na plataforma.',
    'dashboard_card_professional_btn' => 'Editar cadastro',

    'dashboard_card_certification_title' => 'Verificação e selo',
    'dashboard_card_certification_text' => 'Envie sua solicitação: cadastro e documentos conferidos. Taxa simbólica a combinar; por ora sem cobrança. Selo exibido na plataforma após aprovação.',
    'dashboard_card_certification_btn' => 'Solicitar verificação',

    'dashboard_card_messages_title' => 'Mensagens',
    'dashboard_card_messages_text' => 'Em breve: histórico de conversas com clientes que entrarem em contato pelo site.',
    'dashboard_card_messages_btn' => 'Ver mensagens',

    'dashboard_card_history_title' => 'Histórico de serviços',
    'dashboard_card_history_text' => 'Em breve: lista dos serviços realizados e contratados pela plataforma.',
    'dashboard_card_history_btn' => 'Ver histórico',

    'dashboard_card_files_title' => 'Arquivos do perfil',
    'dashboard_card_files_text' => 'Foto para o diretório público, documentos para verificação e fotos da sua vitrine.',
    'dashboard_card_files_btn' => 'Gerenciar arquivos',

    'professional_files_page_title' => 'Arquivos do perfil',
    'professional_files_intro' => 'Envie sua foto de exibição (aparece na listagem de profissionais), documentos para análise de verificação e fotos públicas para a página do seu perfil.',
    'professional_files_back_dashboard' => 'Voltar ao painel',

    'professional_files_profile_section' => 'Foto de perfil',
    'professional_files_profile_help' => 'Esta imagem aparece no card do diretório em /profissionais. Formatos: JPG, PNG, WebP ou GIF. Até 5 MB.',

    'professional_files_verification_section' => 'Documentos para verificação',
    'professional_files_verification_help' => 'Fotos usadas apenas pela equipe para conferir seu cadastro (armazenamento privado). Limite de :max_total arquivos no total e até :max_per tipo por seção (RG, CPF, certificados, diplomas, CNH).',

    'professional_files_doc_rg' => 'RG (identidade)',
    'professional_files_doc_cpf' => 'CPF',
    'professional_files_doc_certificate' => 'Certificados',
    'professional_files_doc_diploma' => 'Diplomas',
    'professional_files_doc_cnh' => 'CNH',
    'professional_files_doc_other' => 'Outros documentos',
    'professional_files_doc_other_hint' => 'Arquivos enviados antes da separação por tipo. Você pode removê-los e enviar de novo nas seções acima, se preferir.',
    'professional_files_doc_section_hint' => 'Fotos nítidas; frente e verso podem ser enviados em arquivos separados.',

    'professional_files_public_section' => 'Fotos públicas (vitrine)',
    'professional_files_public_help' => 'Imagens que poderão ser exibidas na sua página de perfil quando ela estiver disponível. Até :max fotos.',

    'professional_files_btn_choose' => 'Escolher arquivo',
    'professional_files_btn_upload' => 'Enviar',
    'professional_files_confirm_remove_profile' => 'Remover a foto de perfil?',
    'professional_files_confirm_remove_image' => 'Excluir esta imagem? Esta ação não pode ser desfeita.',
    'professional_files_btn_remove_profile' => 'Remover foto de perfil',
    'professional_files_btn_remove' => 'Remover',
    'professional_files_btn_open' => 'Abrir',

    'professional_files_empty_verification' => 'Nenhum documento enviado ainda.',
    'professional_files_empty_public' => 'Nenhuma foto pública ainda.',

    'professional_files_verification_limit' => 'Você pode enviar no máximo :max documentos de verificação no total.',
    'professional_files_verification_limit_per_type' => 'Você pode enviar no máximo :max arquivos nesta seção.',
    'professional_files_public_limit' => 'Você pode enviar no máximo :max fotos públicas no total.',

    'professional_files_status_profile_updated' => 'Foto de perfil atualizada.',
    'professional_files_status_profile_removed' => 'Foto de perfil removida.',
    'professional_files_status_verification_updated' => 'Documentos de verificação enviados.',
    'professional_files_status_public_updated' => 'Fotos públicas enviadas.',
    'professional_files_status_file_removed' => 'Arquivo removido.',

    'professional_verificacao_pagina_titulo' => 'Verificação e selo',
    'professional_verificacao_pagina_intro' => 'Aqui você formaliza a solicitação de verificação. Antes, complete o cadastro profissional, envie a foto de perfil e os documentos (conforme as exigências da sua função). A análise será feita pela equipe em tempo futuro; esta etapa apenas registra o pedido.',
    'professional_verificacao_preco_simbolico' => 'A solicitação de análise prevê taxa simbólica de R$ 19,99 (sem cobrança agora). Pagamento, quando houver, não garante aprovação. A plataforma poderá concluir a análise em até 7 (sete) dias úteis, conforme processo operacional; a tela de decisão (aprovar/reprovar) ainda virá no painel administrativo.',
    'professional_verificacao_preco_futuro' => 'A cobrança ainda não está ativa. Nesta etapa apenas registramos a solicitação na fila interna.',
    'professional_verificacao_ciente_label' => 'Li o aviso acima, incluindo a taxa futura, e solicito entrar na fila de análise.',
    'professional_verificacao_btn_enviar' => 'Solicitar verificação',
    'professional_verificacao_voltar' => 'Voltar ao painel',
    'professional_verificacao_sucesso' => 'Solicitação registrada em fila. A equipe conduzirá a análise (fluxo a ser finalizado).',
    'professional_verificacao_pendente' => 'Já existe uma solicitação aguardando análise. Aguarde a decisão antes de enviar outro pedido.',
    'professional_verificacao_ja_aprovada' => 'Sua verificação já foi aprovada. Não é possível abrir um novo pedido; o selo permanece enquanto o cadastro estiver ativo e conforme as regras da plataforma.',
    'professional_verificacao_cadastro' => 'Cadastro profissional',
    'professional_verificacao_cadastro_sub' => 'Categoria, RG, CPF, título, descrição e valor por hora',
    'professional_verificacao_arquivos' => 'Foto de perfil e documentos',
    'professional_verificacao_arquivos_sub' => 'Inclua fotos legíveis com frente e verso, quando fizer sentido',

    'verificacao_falta_requisito_profissao' => 'Profissão (categoria) e cadastro básico em “Cadastro profissional”.',
    'verificacao_falta_requisito_rg_texto' => 'Número do RG preenchido no cadastro profissional.',
    'verificacao_falta_requisito_cpf_texto' => 'CPF (11 dígitos) preenchido no cadastro.',
    'verificacao_falta_requisito_titulo' => 'Título do anúncio no cadastro.',
    'verificacao_falta_requisito_descricao' => 'Descrição com pelo menos :min caracteres no cadastro.',
    'verificacao_falta_requisito_valor_hora' => 'Valor por hora (maior que zero) no cadastro.',
    'verificacao_falta_requisito_foto_perfil' => 'Foto de perfil enviada; nos documentos, as fotos devem incluir frente e verso, quando o documento tiver dois lados.',
    'verificacao_falta_requisito_arquivo_rg' => 'Fotos do RG na seção de verificação: com frente e verso, em arquivos nítidos.',
    'verificacao_falta_requisito_arquivo_cpf' => 'Fotos do CPF com frente e verso na seção de verificação, conforme o modelo exibido no documento.',
    'verificacao_falta_requisito_arquivo_cnh' => 'Fotos da CNH, frente e verso, exigida pela categoria; na seção correspondente.',
    'verificacao_falta_requisito_arquivo_certificado' => 'Fotos do(s) certificado(s) exigido(s), frente (e verso, se o documento tiver), na seção “Certificados”.',
    'verificacao_falta_requisito_arquivo_diploma' => 'Fotos do(s) diploma(s) exigido(s), frente (e verso, se o documento tiver), na seção “Diplomas”.',

    'verificacao_historico_titulo' => 'Historico de pedidos',
    'verificacao_historico_placeholder' => 'Selecione um registro...',
    'verificacao_historico_situacao_pendente' => 'Em analise',
    'verificacao_historico_situacao_aprovada' => 'Aprovada',
    'verificacao_historico_situacao_reprovada' => 'Reprovada',
    'verificacao_historico_data' => 'Data :data',
    'verificacao_historico_responsavel' => 'Responsável (decisão): :nome',
    'verificacao_historico_responsavel_ausente' => 'Ainda nao atribuida',
    'verificacao_historico_sem_obs' => 'Sem observações.',
    'verificacao_historico_clique_detalhes' => 'Clique no pedido para ver a análise no modal.',
    'verificacao_historico_item_resumo_pendente' => 'Ainda aguardando decisão',
    'verificacao_historico_momento_aprovacao' => 'Aprovação registrada em :quando',
    'verificacao_historico_momento_reprovacao' => 'Reprovação registrada em :quando',
    'verificacao_historico_data_hora' => 'às :hora',

    'verificacao_modal_titulo' => 'Detalhes do pedido de verificação',
    'verificacao_modal_ajuda' => 'Após a análise, esta tela exibirá a data, o responsável e as observações.',
    'verificacao_modal_campo_id' => 'Número do pedido',
    'verificacao_modal_campo_solicitado' => 'Data e hora do pedido',
    'verificacao_modal_campo_situacao' => 'Situação do pedido',
    'verificacao_modal_campo_decisao_datahora' => 'Conclusão da análise',
    'verificacao_modal_rotulo_hora_pendente' => 'Conclusão da análise (data/hora do resultado)',
    'verificacao_modal_rotulo_hora_aprovada' => 'Data e hora da aprovação',
    'verificacao_modal_rotulo_hora_reprovada' => 'Data e hora da reprovação',
    'verificacao_modal_campo_responsavel' => 'Responsável pela análise',
    'verificacao_modal_campo_observacoes' => 'Observações da análise',
    'verificacao_modal_decisao_pendente' => 'Pedido em análise — sem data de aprovação ou reprovação.',
    'verificacao_modal_decisao_sem_data' => 'Ainda sem registro de data/hora. Será exibido quando a equipe finalizar a análise.',
    'verificacao_modal_fechar' => 'Fechar',

    'verificacao_falta_titulo' => 'Ajuste o que faltar (antes de enviar):',
    'verificacao_checklist_tudo_pronto' => 'Pré-checagem: nenhum requisito faltando no cadastro. Você pode enviar a solicitação.',
    'verificacao_checklist_titulo' => 'O que ainda precisa (pré-checagem no servidor no envio):',
    'verificacao_form_bloqueado' => 'Envio bloqueado: já existe um pedido em análise.',
    'verificacao_form_bloqueado_ja_aprovada' => 'Novo envio desativado: verificação já aprovada.',

    'verificacao_selo_aria' => 'Profissional com verificacao aprovada',

    'dashboard_card_password_title' => 'Segurança da conta',
    'dashboard_card_password_text' => 'Altere sua senha periodicamente. Use combinação longa, com letras, números e símbolos.',
    'dashboard_card_password_btn' => 'Trocar senha',

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
