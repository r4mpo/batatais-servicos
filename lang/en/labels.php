<?php

/*
|--------------------------------------------------------------------------
| Application labels — English
|--------------------------------------------------------------------------
|
| Keys mirrored from lang/pt_BR/labels.php. When APP_LOCALE=en, missing
| keys fall back to the configured fallback locale (e.g. pt_BR).
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Professional categories (home / services section)
    |--------------------------------------------------------------------------
    */

    'professional_categories_title' => 'Professional categories',
    'professional_categories_view_all' => 'View all',
    'professional_categories_view_professionals' => 'View professionals',
    'professional_categories_empty' => 'No categories available at the moment.',

    /*
    |--------------------------------------------------------------------------
    | Professionals listing
    |--------------------------------------------------------------------------
    */

    'professionals_page_title' => 'Professionals',
    'professionals_filters_heading' => 'Filters',
    'professionals_search_label' => 'Search',
    'professionals_search_placeholder' => 'Search professionals...',
    'professionals_sort_heading' => 'Sort by',
    'professionals_sort_relevance' => 'Most relevant',
    'professionals_sort_rating' => 'Best rated',
    'professionals_sort_price_asc' => 'Lowest hourly rate',
    'professionals_sort_price_desc' => 'Highest hourly rate',
    'professionals_sort_recent' => 'Newest',
    'professionals_category_heading' => 'Category',
    'professionals_category_select_placeholder' => 'Search and select categories…',
    'professionals_category_select_hint' => 'Use the field above to search and select one or more categories.',
    'professionals_rating_heading' => 'Rating',
    'professionals_rating_5' => '5 stars',
    'professionals_rating_4_plus' => '4+ stars',
    'professionals_price_heading' => 'Price range (per hour)',
    'professionals_price_up_to' => 'Up to',
    'professionals_availability_heading' => 'Availability',
    'professionals_avail_today' => 'Available today',
    'professionals_avail_week' => 'Available this week',
    'professionals_avail_24h' => 'Available 24h',
    'professionals_apply_filters' => 'Apply filters',
    'professionals_clear_filters' => 'Clear filters',
    'professionals_showing' => 'Showing',
    'professionals_total_label' => 'professionals',
    'professionals_empty' => 'No professionals match the selected filters.',
    'professionals_reviews' => ':count reviews',
    'professionals_per_hour' => 'hr',
    'professionals_view_profile' => 'View profile',
    'professionals_contact' => 'Contact',
    'professionals_pagination_label' => 'Listing pagination',
    'professionals_rating_stars_label' => ':n out of 5 stars',
    'nav_professionals' => 'Professionals',

    /*
    |--------------------------------------------------------------------------
    | Professional onboarding
    |--------------------------------------------------------------------------
    */

    'professional_onboarding_title' => 'Complete your professional profile',
    'professional_onboarding_subtitle' => 'Add your details, documents, and how you appear on the platform. You may also change your password here.',
    'professional_onboarding_profession' => 'Professional category',
    'professional_onboarding_profession_placeholder' => 'Select…',
    'professional_onboarding_profession_hint' => 'Pick the category that best matches the service you provide. Use the search when the field is open to filter by name.',
    'professional_onboarding_description_counter' => ':current / :max characters',
    'professional_onboarding_documents_section' => 'Documents and identification',
    'professional_onboarding_service_section' => 'Listing and pricing',
    'professional_onboarding_rg' => 'ID (RG)',
    'professional_onboarding_rg_placeholder' => 'E.g. MG-12.345.678-9',
    'professional_onboarding_rg_hint' => 'Your identity document number (letters, numbers, and punctuation allowed).',
    'professional_onboarding_cpf' => 'CPF',
    'professional_onboarding_cpf_placeholder' => '000.000.000-00',
    'professional_onboarding_cpf_hint' => 'Digits only; formatting is applied automatically.',
    'professional_onboarding_cnpj' => 'CNPJ (optional)',
    'professional_onboarding_cnpj_placeholder' => '00.000.000/0001-00',
    'professional_onboarding_cnpj_hint' => 'Fill in only if you operate as a legal entity.',
    'professional_onboarding_title_field' => 'Listing title',
    'professional_onboarding_title_placeholder' => 'E.g. Plumber — Batatais area',
    'professional_onboarding_title_hint' => 'A short phrase that describes what you offer.',
    'professional_onboarding_description' => 'Description',
    'professional_onboarding_description_placeholder' => 'Experience, service areas, availability, and what makes you stand out.',
    'professional_onboarding_description_hint' => 'Optional. Up to 5,000 characters.',
    'professional_onboarding_hourly_rate' => 'Hourly rate (BRL)',
    'professional_onboarding_hourly_placeholder' => '0,00',
    'professional_onboarding_hourly_hint' => 'Between R$ 1.00 and R$ 500.00.',
    'professional_onboarding_password_section' => 'Change password (optional)',
    'professional_onboarding_password' => 'New password',
    'professional_onboarding_password_confirm' => 'Confirm new password',
    'professional_onboarding_password_confirm_placeholder' => 'Repeat the new password',
    'professional_onboarding_password_placeholder' => 'Leave blank to keep your current password',
    'professional_onboarding_password_hint' => 'If you change it, choose a strong password (requirements appear below the field if needed).',
    'professional_onboarding_submit' => 'Save and continue',
    'professional_onboarding_success' => 'Your professional profile is set up. Welcome to your dashboard!',
    'professional_profile_updated_success' => 'Your professional details have been updated.',

    /*
    |--------------------------------------------------------------------------
    | Dashboard (authenticated area)
    |--------------------------------------------------------------------------
    */

    'dashboard_title' => 'Dashboard',
    'dashboard_alert_dismiss' => 'Dismiss',

    'dashboard_contractor_title' => 'Welcome to your area',
    'dashboard_contractor_lead' => 'You are signed in. Use the menu to browse the site or open your profile to update account details.',
    'dashboard_contractor_profile_link' => 'Go to my profile',

    'dashboard_professional_lead' => 'Professional hub: manage your listing, review indicators, and get ready for upcoming platform features.',
    'dashboard_finance_title' => 'Financial summary',
    'dashboard_finance_disclaimer' => 'Sample figures — your real earnings and withdrawals will appear here soon.',
    'dashboard_finance_available_withdrawal' => 'Available to withdraw',
    'dashboard_finance_net_available' => 'Net available',
    'dashboard_finance_total_withdrawn' => 'Total withdrawn',
    'dashboard_finance_net_withdrawn' => 'Net withdrawn',
    'dashboard_finance_sample_available' => 'R$ 1,250.00',
    'dashboard_finance_sample_net_available' => 'R$ 1,125.00',
    'dashboard_finance_sample_total_withdrawn' => 'R$ 8,500.00',
    'dashboard_finance_sample_net_withdrawn' => 'R$ 7,650.00',

    'dashboard_card_professional_title' => 'Professional profile',
    'dashboard_card_professional_text' => 'Update category, documents (ID, CPF, CNPJ), listing title, description, and hourly rate shown on the platform.',
    'dashboard_card_professional_btn' => 'Edit registration',

    'dashboard_card_certification_title' => 'Verification badge',
    'dashboard_card_certification_text' => 'Submit a request: complete your listing and files. A symbolic fee may apply; no charge yet. Verified badge shown after approval.',
    'dashboard_card_certification_btn' => 'Request verification',

    'dashboard_card_messages_title' => 'Messages',
    'dashboard_card_messages_text' => 'Coming soon: conversation history with clients who contact you through the site.',
    'dashboard_card_messages_btn' => 'View messages',

    'dashboard_card_history_title' => 'Service history',
    'dashboard_card_history_text' => 'Coming soon: list of services completed through the platform.',
    'dashboard_card_history_btn' => 'View history',

    'dashboard_card_files_title' => 'Profile files',
    'dashboard_card_files_text' => 'Photo for the public directory, documents for verification, and showcase photos.',
    'dashboard_card_files_btn' => 'Manage files',

    'professional_files_page_title' => 'Profile files',
    'professional_files_intro' => 'Upload your display photo (shown on the professionals directory), verification documents for review, and public photos for your future profile page.',
    'professional_files_back_dashboard' => 'Back to dashboard',

    'professional_files_profile_section' => 'Profile photo',
    'professional_files_profile_help' => 'Shown on the professional cards in /profissionais. Formats: JPG, PNG, WebP, or GIF. Up to 5 MB.',

    'professional_files_verification_section' => 'Verification documents',
    'professional_files_verification_help' => 'Photos used only by staff to verify your registration (private storage). Up to :max_total files overall and up to :max_per section for each type (ID, CPF, certificates, diplomas, driver license).',

    'professional_files_doc_rg' => 'Government ID (RG)',
    'professional_files_doc_cpf' => 'CPF',
    'professional_files_doc_certificate' => 'Certificates',
    'professional_files_doc_diploma' => 'Diplomas',
    'professional_files_doc_cnh' => 'Driver license (CNH)',
    'professional_files_doc_other' => 'Other documents',
    'professional_files_doc_other_hint' => 'Files uploaded before document types were split. You may delete them and re-upload under the sections above.',
    'professional_files_doc_section_hint' => 'Use clear photos; front and back can be separate files.',

    'professional_files_public_section' => 'Public photos (showcase)',
    'professional_files_public_help' => 'Images that may appear on your profile page when it launches. Up to :max photos.',

    'professional_files_btn_choose' => 'Choose file',
    'professional_files_btn_upload' => 'Upload',
    'professional_files_confirm_remove_profile' => 'Remove the profile photo?',
    'professional_files_confirm_remove_image' => 'Delete this image? This cannot be undone.',
    'professional_files_btn_remove_profile' => 'Remove profile photo',
    'professional_files_btn_remove' => 'Remove',
    'professional_files_btn_open' => 'Open',

    'professional_files_empty_verification' => 'No verification documents uploaded yet.',
    'professional_files_empty_public' => 'No public photos yet.',

    'professional_files_verification_limit' => 'You can upload at most :max verification documents in total.',
    'professional_files_verification_limit_per_type' => 'You can upload at most :max files in this section.',
    'professional_files_public_limit' => 'You can upload at most :max public photos in total.',

    'professional_files_status_profile_updated' => 'Profile photo updated.',
    'professional_files_status_profile_removed' => 'Profile photo removed.',
    'professional_files_status_verification_updated' => 'Verification documents uploaded.',
    'professional_files_status_public_updated' => 'Public photos uploaded.',
    'professional_files_status_file_removed' => 'File removed.',

    'professional_verificacao_pagina_titulo' => 'Verification and badge',
    'professional_verificacao_pagina_intro' => 'This page records your verification request. Complete your professional profile, profile photo, and required documents. Staff approval will be implemented later; this step only enqueues the request.',
    'professional_verificacao_preco_simbolico' => 'Analysis may include a symbolic fee of R$ 19,99; it is not charged for now. Payment, when required, does not guarantee approval. The team may take up to 7 business days to process; the decision UI will be added in an admin area.',
    'professional_verificacao_preco_futuro' => 'Billing is not active yet. Only the internal queue is recorded in this step.',
    'professional_verificacao_ciente_label' => 'I have read the notice above, including the future fee, and request a place in the review queue.',
    'professional_verificacao_btn_enviar' => 'Submit request',
    'professional_verificacao_voltar' => 'Back to dashboard',
    'professional_verificacao_sucesso' => 'Request added to the queue. Review will follow the upcoming workflow.',
    'professional_verificacao_pendente' => 'You already have a request under review. Wait for a decision before sending another one.',
    'professional_verificacao_cadastro' => 'Professional profile',
    'professional_verificacao_cadastro_sub' => 'Category, ID, CPF, title, description, hourly rate',
    'professional_verificacao_arquivos' => 'Profile photo and documents',
    'professional_verificacao_arquivos_sub' => 'Use clear photos; for each document, include front and back when applicable',

    'verificacao_falta_requisito_profissao' => 'Profession (category) selected in the professional profile.',
    'verificacao_falta_requisito_rg_texto' => 'ID (RG) number filled in the professional profile.',
    'verificacao_falta_requisito_cpf_texto' => 'CPF (11 digits) in the professional profile.',
    'verificacao_falta_requisito_titulo' => 'Listing title in the professional profile.',
    'verificacao_falta_requisito_descricao' => 'Description with at least :min characters in the professional profile.',
    'verificacao_falta_requisito_valor_hora' => 'Hourly rate above zero in the professional profile.',
    'verificacao_falta_requisito_foto_perfil' => 'Profile photo uploaded. For document photos, include front and back for two-sided items.',
    'verificacao_falta_requisito_arquivo_rg' => 'ID (RG) photos in verification: both front and back, clearly readable.',
    'verificacao_falta_requisito_arquivo_cpf' => 'CPF document photos: front and back, as required by the form on the file.',
    'verificacao_falta_requisito_arquivo_cnh' => 'Driver license (CNH) with front and back, when required for your profession.',
    'verificacao_falta_requisito_arquivo_certificado' => 'Certificate photo(s) required, front (and back if the certificate has one), in the Certificates section.',
    'verificacao_falta_requisito_arquivo_diploma' => 'Diploma photo(s) required, front (and back if the diploma has one), in the Diplomas section.',

    'verificacao_historico_titulo' => 'Request history',
    'verificacao_historico_placeholder' => 'Select an entry...',
    'verificacao_historico_situacao_pendente' => 'Under review',
    'verificacao_historico_situacao_aprovada' => 'Approved',
    'verificacao_historico_situacao_reprovada' => 'Not approved',
    'verificacao_historico_data' => 'Date: :data',
    'verificacao_historico_responsavel' => 'By :nome',
    'verificacao_historico_responsavel_ausente' => 'Not set yet',
    'verificacao_historico_sem_obs' => 'No notes.',
    'verificacao_historico_clique_detalhes' => 'Click a request to open the full details in a dialog.',
    'verificacao_historico_item_resumo_pendente' => 'Still awaiting a decision',
    'verificacao_historico_momento_aprovacao' => 'Approved on :quando',
    'verificacao_historico_momento_reprovacao' => 'Not approved on :quando',
    'verificacao_historico_data_hora' => 'at :hora',
    
    'verificacao_modal_titulo' => 'Verification request details',
    'verificacao_modal_ajuda' => 'After the review, this will show the decision time, responsible user, and notes.',
    'verificacao_modal_campo_id' => 'Request number',
    'verificacao_modal_campo_solicitado' => 'Requested at',
    'verificacao_modal_campo_situacao' => 'Status',
    'verificacao_modal_campo_decisao_datahora' => 'When the review was completed',
    'verificacao_modal_rotulo_hora_pendente' => 'Review outcome (date and time)',
    'verificacao_modal_rotulo_hora_aprovada' => 'Approval date and time',
    'verificacao_modal_rotulo_hora_reprovada' => 'Rejection date and time',
    'verificacao_modal_campo_responsavel' => 'Reviewer',
    'verificacao_modal_campo_observacoes' => 'Review notes',
    'verificacao_modal_decisao_pendente' => 'Under review — no approval or rejection time yet.',
    'verificacao_modal_decisao_sem_data' => 'Decision timestamp not recorded yet. It will appear when staff completes the review.',
    'verificacao_modal_fechar' => 'Close',

    'verificacao_falta_titulo' => 'Address these items before your request is accepted:',
    'verificacao_checklist_tudo_pronto' => 'Prerequisite check: nothing missing. You can submit the request.',
    'verificacao_checklist_titulo' => 'Current gaps (re-validated on submit):',
    'verificacao_form_bloqueado' => 'Submit is disabled: a request is already under review.',
    'verificacao_selo_aria' => 'Professionally verified',

    'dashboard_card_password_title' => 'Account security',
    'dashboard_card_password_text' => 'Change your password regularly. Prefer a long mix of letters, numbers, and symbols.',
    'dashboard_card_password_btn' => 'Change password',

];
