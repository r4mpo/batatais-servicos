# 🥔 Batatais Serviços

> Plataforma web para conectar **clientes (contratantes)** e **profissionais de serviços** em Batatais e região — listagem pública, cadastro, verificação, histórico de serviços e resumo financeiro do profissional.

[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white)](https://laravel.com/)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

---

## 📋 Índice

- [Sobre o projeto](#-sobre-o-projeto)
- [Stack tecnológica](#-stack-tecnológica)
- [Arquitetura](#-arquitetura)
- [Requisitos](#-requisitos)
- [Instalação passo a passo](#-instalação-passo-a-passo)
- [Banco de dados e migrations](#-banco-de-dados-e-migrations)
- [Seeders](#-seeders)
- [Executando o projeto](#-executando-o-projeto)
- [Testes](#-testes)
- [Rotas principais](#-rotas-principais)
- [Perfis de usuário](#-perfis-de-usuário)
- [Modelo de serviços](#-modelo-de-serviços)
- [Internacionalização](#-internacionalização)
- [Estrutura de pastas](#-estrutura-de-pastas)
- [Produtividade com Cursor](#-produtividade-com-cursor)
- [Próximos passos](#-próximos-passos)
- [Licença](#-licença)

---

## 🎯 Sobre o projeto

O **Batatais Serviços** é um marketplace local de prestação de serviços. Hoje a aplicação oferece:

| Área | O que já existe |
|------|-----------------|
| 🏠 **Página inicial** | Apresentação, categorias e destaques |
| 🔍 **Diretório** | Listagem pública em `/profissionais` com filtros |
| 👤 **Autenticação** | Registro, login, verificação de e-mail (Laravel Breeze) |
| 🧑‍🔧 **Profissional** | Onboarding, arquivos (foto, documentos, vitrine), solicitação de selo de verificação |
| 📊 **Dashboard** | Hub do profissional com resumo financeiro baseado em serviços concluídos |
| 📜 **Histórico** | Lista estilizada dos serviços do profissional com endereço, período, valores e feedbacks |

O projeto prioriza **código legível**, convenções Laravel e separação em camadas (Controllers finos, Services, Repositories, Enums).

---

## 🛠 Stack tecnológica

| Camada | Tecnologia |
|--------|------------|
| Backend | **PHP 8.3**, **Laravel 12** |
| Autenticação | **Laravel Breeze** (sessão, Blade) |
| Banco | **SQLite** (padrão local), **MySQL/MariaDB** ou **SQL Server** |
| Front (assets) | **Vite 7**, **Tailwind CSS 3**, **Alpine.js** |
| UI pública | **Bootstrap 5**, **Font Awesome 6** |
| Testes | **PHPUnit 11** |
| Qualidade | **Laravel Pint** |

---

## 🏗 Arquitetura

A aplicação segue uma **arquitetura em camadas** inspirada no padrão Laravel, com responsabilidades bem definidas:

```
┌─────────────────────────────────────────────────────────────────┐
│  Browser (Blade + Bootstrap / CSS dedicado por página)          │
└────────────────────────────┬────────────────────────────────────┘
                             │
┌────────────────────────────▼────────────────────────────────────┐
│  Routes (web.php, auth.php) + Middleware                        │
│  • auth, verified                                               │
│  • EnsureProfessionalRegistrationComplete                       │
└────────────────────────────┬────────────────────────────────────┘
                             │
┌────────────────────────────▼────────────────────────────────────┐
│  Controllers (HTTP) — chamam a service e Controller::responder │
│  Ex.: DashboardController, ProfessionalServiceHistoryController │
└────────────────────────────┬────────────────────────────────────┘
                             │
        ┌────────────────────┼────────────────────┐
        ▼                    ▼                    ▼
┌───────────────┐   ┌─────────────────┐   ┌──────────────────┐
│ Form Requests │   │ Services        │   │ Repositories     │
│ (validação)   │   │ (regras de      │   │ (consultas       │
│               │   │  negócio)       │   │  complexas)      │
└───────────────┘   └────────┬────────┘   └────────┬─────────┘
                             │                     │
                             └──────────┬──────────┘
                                        ▼
                             ┌──────────────────┐
                             │ Models (Eloquent) │
                             │ Enums, Support    │
                             └────────┬─────────┘
                                      ▼
                             ┌─────────────────────────────┐
                             │ SQL Server / MySQL / SQLite │
                             └─────────────────────────────┘
```

### 📦 Camadas em detalhe

| Pasta | Papel |
|-------|--------|
| `app/Http/Controllers` | Entrada HTTP do domínio: Form Request, uma chamada à service e `responder()` |
| `app/Http/Responses` | `ResultadoResposta`: descreve página, redirect, arquivo ou erro HTTP sem montar a resposta |
| `app/Http/Requests` | Validação e normalização (CPF, valores em reais, uploads, exclusão de conta) |
| `app/Http/Middleware` | Regras transversais (ex.: profissional sem cadastro → redirect setup) |
| `app/Services` | Regras de negócio: onboarding, arquivos, verificação, listagem, histórico, dashboard e perfil |
| `app/Repositories` | Queries reutilizáveis (profissionais, profissões, usuários) |
| `app/Models` | Entidades Eloquent + relacionamentos |
| `app/Enums` | Estados tipados (ex.: `ServiceStatus`) |
| `app/Support` | Utilitários (documentos brasileiros, formatação) |
| `resources/views` | Blade; CSS em `public/css/` por feature |
| `lang/pt_BR` e `lang/en` | Traduções centralizadas em `labels.php` |
| `database/migrations` | Versionamento do schema |
| `database/seeders` | Dados de demonstração |

### 🔐 Fluxo do profissional (simplificado)

1. **Registro / Login**
   1. Verificar se o perfil é `001`.
   2. Verificar se existe cadastro em `professionals`.
      - **Não:** redirecionar para `/area-profissional/cadastro`.
      - **Sim:** acessar o **Dashboard**.
        - Histórico de serviços
        - Arquivos / Verificação

### 💰 Resumo financeiro (profissional)

Valores calculados a partir da tabela `services`:

- **Disponível para saque**: serviços com status **Concluído (7)** e `value_withdrawn = false` (soma bruta).
- **Líquido disponível**: 90% do disponível (taxa de plataforma de 10%).
- **Total sacado / Líquido sacado**: mesma lógica para serviços concluídos já sacados.

O cálculo continua em `App\Models\Service::financeSummaryForProfessionalUser()`. O `DashboardService` decide quando exibi-lo e devolve um `ResultadoResposta` de página; o controller só chama `responder()`.

---

## ✅ Requisitos

Antes de instalar, garanta:

| Ferramenta | Versão mínima |
|------------|----------------|
| PHP | 8.3 (extensões: `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`) |
| Composer | 2.x |
| Node.js | 18+ (recomendado 20+) |
| npm | 9+ |

Opcional: **MySQL 8+**, **MariaDB** ou **SQL Server** (extensão `pdo_sqlsrv` e ODBC Driver) para ambientes que não usem SQLite.

---

## 🚀 Instalação passo a passo

### 1️⃣ Clonar o repositório

```bash
git clone <url-do-repositorio> batatais-servicos
cd batatais-servicos
```

### 2️⃣ Instalar dependências PHP

```bash
composer install
```

### 3️⃣ Configurar ambiente

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` conforme necessário. Exemplo para **SQLite** (padrão do projeto):

```env
APP_NAME="Batatais Serviços"
APP_URL=http://127.0.0.1:8000
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR

DB_CONNECTION=sqlite
# DB_DATABASE usa database/database.sqlite por padrão no Laravel
```

Crie o arquivo do banco, se ainda não existir:

```bash
# Windows (PowerShell)
New-Item -ItemType File -Path database\database.sqlite -Force

# Linux / macOS
touch database/database.sqlite
```

Exemplo para **MySQL**:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=batatais_servicos
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

Exemplo para **SQL Server**:

```env
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=batatais_servicos
DB_USERNAME=laravel
DB_PASSWORD=sua_senha
DB_ENCRYPT=yes
DB_TRUST_SERVER_CERTIFICATE=false
```

`DB_ENCRYPT` aceita `yes` ou `no`. `DB_TRUST_SERVER_CERTIFICATE` aceita `true` ou `false`. Em desenvolvimento local com certificado autoassinado, use `DB_ENCRYPT=no` ou `DB_TRUST_SERVER_CERTIFICATE=true`. É necessária a extensão PHP `pdo_sqlsrv` e o ODBC Driver for SQL Server.

### 4️⃣ Rodar migrations

```bash
php artisan migrate
```

Para ambiente limpo (⚠️ apaga todos os dados):

```bash
php artisan migrate:fresh
```

### 5️⃣ Popular o banco (seeders)

```bash
php artisan db:seed
```

Ou um seeder específico:

```bash
php artisan db:seed --class=ServiceDemoSeeder
```

### 6️⃣ Instalar e compilar assets front-end

```bash
npm install
npm run build
```

### 7️⃣ Link simbólico de storage (uploads)

```bash
php artisan storage:link
```

### ⚡ Instalação rápida (script Composer)

O projeto inclui um atalho que executa boa parte do fluxo:

```bash
composer setup
```

Equivale a: `composer install` → copiar `.env` → `key:generate` → `migrate` → `npm install` → `npm run build`.

> Depois do `composer setup`, rode `php artisan db:seed` manualmente se quiser dados de demonstração.

---

## 🗄 Banco de dados e migrations

As migrations ficam em `database/migrations/`. Principais tabelas:

| Tabela | Descrição |
|--------|-----------|
| `users` | Contas; campo `profile` (`000` contratante, `001` profissional) |
| `practice_areas` | Áreas de atuação (agrupamento) |
| `professions` | Profissões/categorias |
| `professionals` | Cadastro do anúncio (título, descrição, valor/hora, documentos) |
| `professional_availabilities` | Disponibilidade por dia da semana |
| `professional_reviews` | Avaliações na listagem |
| `professional_files` | Fotos e documentos (público / verificação) |
| `professional_verification_requests` | Fila de selo de verificação |
| `services` | Serviços entre contratante e profissional |

### Criar uma nova migration

```bash
php artisan make:migration create_nome_da_tabela_table
# ou alteração:
php artisan make:migration add_campo_to_tabela_table --table=tabela
```

Exemplo de estrutura (padrão do projeto):

```php
Schema::create('services', function (Blueprint $table) {
    $table->id();
    $table->foreignId('contractor_user_id')->constrained('users');
    $table->foreignId('professional_user_id')->constrained('users');
    $table->unsignedTinyInteger('status');
    $table->unsignedBigInteger('service_value_cents');
    $table->boolean('value_withdrawn')->default(false);
    $table->timestamps();
    $table->softDeletes();
});
```

### SQL Server

As migrations rodam no SQL Server com três cuidados que o MySQL e o SQLite não exigem:

- Não use `restrictOnDelete()`. O T-SQL rejeita `ON DELETE RESTRICT`. Sem essa cláusula, o padrão já é `NO ACTION`, o mesmo efeito de impedir apagar o pai enquanto existir filho.
- Não crie dois caminhos de `CASCADE` (ou `SET NULL`) entre as mesmas tabelas. Por isso `professional_reviews.user_id` e `services` não cascateiam a partir de `users`: a avaliação é apagada junto com o profissional, e ao excluir a conta o model `User` remove as avaliações que a pessoa escreveu e zera `decided_by_user_id`.
- `cpf` e `cnpj` podem ficar vazios em vários profissionais. No SQL Server um unique comum aceita um único `NULL`. A migration `2026_09_24_200000_use_filtered_unique_indexes_for_professional_documents_on_sqlsrv` troca esses índices por unique filtrado (`WHERE coluna IS NOT NULL`) só nesse driver.

A suíte PHPUnit usa SQLite em memória (`phpunit.xml`) e não precisa do SQL Server.

Aplicar:

```bash
php artisan migrate
```

Reverter último lote:

```bash
php artisan migrate:rollback
```

---

## 🌱 Seeders

Seeders vivem em `database/seeders/` e são registrados no `DatabaseSeeder`:

```php
$this->call([
    PracticeAreaSeeder::class,
    ProfessionSeeder::class,
    ProfessionalDemoSeeder::class,
    ServiceDemoSeeder::class,
]);
```

| Seeder | Função |
|--------|--------|
| `PracticeAreaSeeder` | Áreas de prática (categorias macro) |
| `ProfessionSeeder` | Centenas de profissões com ícones e slugs |
| `ProfessionalDemoSeeder` | ~14 profissionais demo + avaliações e disponibilidade |
| `ServiceDemoSeeder` | Serviços de exemplo para `profissional.demo.1@example.test` |

### Criar um novo seeder

```bash
php artisan make:seeder MeuNovoSeeder
```

Template mínimo:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MeuNovoSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();
        // Model::query()->create([...]);
    }
}
```

Registre no `DatabaseSeeder` ou execute isolado:

```bash
php artisan db:seed --class=MeuNovoSeeder
```

### 👤 Credenciais de teste (após seed)

Profissionais demo seguem o padrão de e-mail:

```text
profissional.demo.1@example.test
profissional.demo.2@example.test
...
```

A senha é gerada pelo `UserFactory` (consulte `database/factories/UserFactory.php`). Para login manual após seed, crie um usuário com:

```bash
php artisan tinker
```

```php
\App\Models\User::factory()->create([
    'email' => 'teste@example.test',
    'password' => bcrypt('senha-segura'),
    'profile' => \App\Models\User::PROFILE_PROFESSIONAL,
]);
```

> O `ServiceDemoSeeder` só insere dados se o profissional `profissional.demo.1@example.test` existir e ainda não houver serviços para ele.

---

## ▶️ Executando o projeto

### Modo desenvolvimento (recomendado)

Sobe servidor PHP, fila, logs e Vite em paralelo:

```bash
composer dev
```

### Modo manual

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Acesse: **http://127.0.0.1:8000**

### Formatar código PHP

```bash
vendor/bin/pint
# ou apenas arquivos alterados:
vendor/bin/pint --dirty
```

---

## 🧪 Testes

```bash
composer test
# equivalente a:
php artisan config:clear --ansi
php artisan test
```

Testes de feature ficam em `tests/Feature/` (onboarding, autenticação, verificação, etc.). O `phpunit.xml` força SQLite em memória, mesmo quando o `.env` aponta para SQL Server.

---

## 🗺 Rotas principais

| Método | URI | Nome | Descrição |
|--------|-----|------|-----------|
| GET | `/` | `home` | Página inicial |
| GET | `/profissionais` | `professionals.index` | Diretório público |
| GET | `/dashboard` | `dashboard` | Painel (auth) |
| GET | `/area-profissional/cadastro` | `professional.setup` | Onboarding profissional |
| GET | `/area-profissional/historico-servicos` | `professional.services.history` | Histórico de serviços |
| GET | `/area-profissional/arquivos` | `professional.files` | Fotos e documentos |
| GET | `/area-profissional/verificacao` | `professional.verificacao` | Solicitar selo |
| GET | `/profile` | `profile.edit` | Perfil da conta |

Rotas de autenticação: `routes/auth.php` (login, registro, reset de senha, verificação de e-mail).

---

## 👥 Perfis de usuário

Constantes em `App\Models\User`:

```php
const PROFILE_CONTRACTOR = '000';    // Cliente / contratante
const PROFILE_PROFESSIONAL = '001';  // Prestador de serviços

public function isProfessional(): bool
{
    return $this->profile === self::PROFILE_PROFESSIONAL;
}
```

No registro, o usuário escolhe o perfil; profissionais passam pelo middleware `EnsureProfessionalRegistrationComplete` até completar `professionals`.

---

## 📋 Modelo de serviços

Enum `App\Enums\ServiceStatus`:

| Código | Significado |
|--------|-------------|
| 1 | Pagamento pendente |
| 2 | Em análise |
| 3 | Aceito pelo profissional |
| 4 | Em fase de prestação |
| 5 | Manutenção |
| 6 | Suporte |
| 7 | Concluído |

Campos principais em `services` (além de status e valor):

- `title`, `description`
- Endereço: `address_postal_code`, `address_street`, `address_number`, `address_complement`, `address_neighborhood`, `address_city`, `address_state`
- Agendamento: `scheduled_start_date`, `scheduled_end_date`, `scheduled_start_time`, `scheduled_end_time`
- `contractor_feedback`, `professional_feedback`, `value_withdrawn`

A listagem paginada do histórico fica em `ProfessionalServiceHistoryService` (12 itens por página). O controller não consulta o Eloquent.

---

## 🌐 Internacionalização

Textos de interface em:

- `lang/pt_BR/labels.php` (principal)
- `lang/en/labels.php`

Configure no `.env`:

```env
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
```

Uso nas views:

```blade
{{ __('labels.dashboard_finance_title') }}
```

---

## 📁 Estrutura de pastas (resumo)

```text
batatais-servicos/
├── app/
│   ├── Enums/              # ServiceStatus, etc.
│   ├── Http/
│   │   ├── Controllers/    # domínio fino; Auth do Breeze permanece no fluxo padrão
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   └── Responses/      # ResultadoResposta
│   ├── Models/
│   ├── Repositories/
│   ├── Services/
│   └── Support/
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
├── lang/
│   ├── pt_BR/
│   └── en/
├── public/
│   ├── css/                # dashboard.css, service-history.css, ...
│   └── img/
├── resources/
│   └── views/
│       ├── professional/
│       ├── profile/
│       └── layouts/
├── routes/
│   ├── web.php
│   └── auth.php
└── tests/
```

---

## ⚡ Produtividade com Cursor

> **Este projeto foi desenvolvido e evoluído com forte apoio do [Cursor](https://cursor.com) — e a diferença na velocidade de entrega é enorme.**

O **Cursor** não é “só mais um editor”: é um ambiente onde IA e código convivem no mesmo fluxo de trabalho. Para o Batatais Serviços, isso significou:

| Benefício | Na prática |
|-----------|------------|
| 🧠 **Contexto do repositório** | A IA lê migrations, models, seeders e padrões existentes antes de sugerir código — menos divergência de nomenclatura (`snake_case`, soft deletes, FKs). |
| 🔁 **Refatoração segura** | Renomear campos, criar Enums, extrair Services e atualizar Blade/labels em lote, com revisão humana. |
| 📐 **Scaffolding alinhado** | Migrations, seeders, controllers e views seguindo o que já existe no projeto, não um Laravel genérico. |
| 🐛 **Debug mais rápido** | Erros de migration, rotas e Eloquent investigados com o terminal e os arquivos abertos no mesmo chat. |
| 📚 **Documentação viva** | Este README, comentários em middleware e docblocks em português gerados e mantidos junto com o código. |

### Como tirar o máximo proveito

1. **Descreva o padrão** — “siga as outras tabelas”, “use `labels.php`”, “controller fino + service”.
2. **Peça mudanças pequenas e encadeadas** — migration → model → seeder → view → rota.
3. **Use o Agent com o projeto aberto** — ele executa `artisan migrate`, `db:seed` e testes quando necessário.
4. **Revise sempre** — IA acelera; você valida regra de negócio, segurança e UX.

**Conclusão:** para um time pequeno ou um desenvolvedor solo construindo uma plataforma completa (auth, uploads, verificação, serviços, financeiro), o Cursor reduz drasticamente o tempo entre “ideia” e “PR mergeável”. **Recomendamos fortemente** Cursor (ou fluxo equivalente Agent + codebase indexing) para continuar os próximos passos listados abaixo.

---

## 🗓 Próximos passos

Roadmap planejado para evolução da plataforma:

### 1. 📄 Paginação do histórico de serviços do profissional

Refinar a paginação em `/area-profissional/historico-servicos` (já existe `paginate(12)` em `ProfessionalServiceHistoryService`): filtros por status, busca por título/contratante, ordenação e UX mobile da navegação entre páginas.

### 2. 🖼 Página de perfil / portfólio do profissional

Página pública (ou semi-pública) com **todas** as informações condizentes: bio, profissão, valor/hora, disponibilidade, fotos da vitrine, avaliações, selo de verificação e CTA para contratar.

### 3. 💬 Sistema de mensagens

Canal de comunicação **cliente ↔ profissional** (threads por serviço ou por contato), com notificações e histórico persistente.

### 4. 📝 Controle de serviços (fluxo do cliente)

Fluxo em que o **cliente cria** o serviço, define valor e período, **paga** (inicialmente simulado / sem gateway) e o **profissional aceita** ou recusa — integrado ao enum de status existente.

### 5. 💳 Pagamentos, saques e estornos

Integração com gateway para:

- pagamento de **serviços**;
- taxa/saque do profissional (`value_withdrawn`, resumo financeiro);
- pagamentos relacionados ao **selo de verificação** e estornos quando aplicável.

### 6. 🏠 Dashboard do cliente (contratante)

Painel enxuto com apenas:

- **histórico de serviços** (como contratante);
- **histórico de mensagens**.

Sem duplicar cards do fluxo profissional.

### 7. 🌐 Conteúdo institucional e estatísticas reais

- Redes sociais e contatos configuráveis;
- páginas **Termos de uso**, **Sobre nós**, política de privacidade;
- substituir números estáticos da home por **métricas reais** (profissionais ativos, serviços concluídos, etc.).

---

## 📄 Licença

Este projeto está sob a licença [MIT](LICENSE), salvo dependências de terceiros com licenças próprias (Laravel, Bootstrap, etc.).

---

<p align="center">
  Feito com ☕ e <strong>Cursor</strong> para Batatais e região 🥔
</p>
