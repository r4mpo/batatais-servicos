<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContractorServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProfessionalOnboardingController;
use App\Http\Controllers\ProfessionalProfileFilesController;
use App\Http\Controllers\ProfessionalServiceHistoryController;
use App\Http\Controllers\ProfessionalVerificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Middleware\EnsureProfessionalRegistrationComplete;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/sobre-nos', [AboutController::class, 'index'])->name('about');

Route::get('/profissionais', [ProfessionalController::class, 'index'])->name('professionals.index');
Route::get('/profissionais/{professional}', [ProfessionalController::class, 'show'])->name('professionals.show');

Route::get('/media/perfil/{token}', [ProfilePhotoController::class, 'show'])
    ->where('token', '[^/]+')
    ->name('profile.photo');

Route::middleware(['auth', 'verified', EnsureProfessionalRegistrationComplete::class])->group(function () {
    Route::get('/area-profissional/cadastro', [ProfessionalOnboardingController::class, 'edit'])->name('professional.setup');
    Route::post('/area-profissional/cadastro', [ProfessionalOnboardingController::class, 'store'])->name('professional.setup.store');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/area-profissional/historico-servicos', [ProfessionalServiceHistoryController::class, 'index'])
        ->name('professional.services.history');

    Route::get('/area-profissional/arquivos', [ProfessionalProfileFilesController::class, 'edit'])->name('professional.files');
    Route::post('/area-profissional/arquivos/foto-perfil', [ProfessionalProfileFilesController::class, 'atualizarFotoPerfil'])->name('professional.files.profile-photo');
    Route::delete('/area-profissional/arquivos/foto-perfil', [ProfessionalProfileFilesController::class, 'excluirFotoPerfil'])->name('professional.files.profile-photo.destroy');
    Route::post('/area-profissional/arquivos/documentos-verificacao', [ProfessionalProfileFilesController::class, 'armazenarDocumentosVerificacao'])->name('professional.files.verification');
    Route::get('/area-profissional/arquivos/documentos-verificacao/{professional_file}', [ProfessionalProfileFilesController::class, 'exibirDocumentoVerificacao'])->name('professional.files.verification.show');
    Route::post('/area-profissional/arquivos/fotos-publicas', [ProfessionalProfileFilesController::class, 'armazenarFotosPublicas'])->name('professional.files.public');
    Route::delete('/area-profissional/arquivos/arquivo/{professional_file}', [ProfessionalProfileFilesController::class, 'excluirArquivo'])->name('professional.files.destroy');

    Route::get('/area-profissional/verificacao', [ProfessionalVerificationController::class, 'exibirFormulario'])->name('professional.verificacao');
    Route::post('/area-profissional/verificacao', [ProfessionalVerificationController::class, 'armazenar'])->name('professional.verificacao.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/area-cliente/profissionais', [ContractorServiceController::class, 'searchProfessionals'])->name('contractor.professionals.search');
    Route::get('/area-cliente/servicos', [ContractorServiceController::class, 'index'])->name('contractor.services.index');
    Route::get('/area-cliente/servicos/novo', [ContractorServiceController::class, 'create'])->name('contractor.services.create');
    Route::post('/area-cliente/servicos', [ContractorServiceController::class, 'store'])->name('contractor.services.store');
    Route::get('/area-cliente/servicos/{service}', [ContractorServiceController::class, 'show'])->name('contractor.services.show');
    Route::get('/area-cliente/servicos/{service}/editar', [ContractorServiceController::class, 'edit'])->name('contractor.services.edit');
    Route::put('/area-cliente/servicos/{service}', [ContractorServiceController::class, 'update'])->name('contractor.services.update');
    Route::delete('/area-cliente/servicos/{service}', [ContractorServiceController::class, 'destroy'])->name('contractor.services.destroy');
    Route::post('/area-cliente/servicos/{service}/pagamento', [ContractorServiceController::class, 'pay'])->name('contractor.services.pay');
});

require __DIR__.'/auth.php';
