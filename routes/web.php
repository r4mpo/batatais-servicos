<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProfessionalOnboardingController;
use App\Http\Controllers\ProfessionalProfileFilesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoController;
use App\Http\Middleware\EnsureProfessionalRegistrationComplete;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profissionais', [ProfessionalController::class, 'index'])->name('professionals.index');

Route::get('/media/perfil/{token}', [ProfilePhotoController::class, 'show'])
    ->where('token', '[^/]+')
    ->name('profile.photo');

Route::middleware(['auth', 'verified', EnsureProfessionalRegistrationComplete::class,])->group(function () {
    Route::get('/area-profissional/cadastro', [ProfessionalOnboardingController::class, 'edit'])->name('professional.setup');
    Route::post('/area-profissional/cadastro', [ProfessionalOnboardingController::class, 'store'])->name('professional.setup.store');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/area-profissional/arquivos', [ProfessionalProfileFilesController::class, 'edit'])->name('professional.files');
    Route::post('/area-profissional/arquivos/foto-perfil', [ProfessionalProfileFilesController::class, 'updateProfilePhoto'])->name('professional.files.profile-photo');
    Route::delete('/area-profissional/arquivos/foto-perfil', [ProfessionalProfileFilesController::class, 'destroyProfilePhoto'])->name('professional.files.profile-photo.destroy');
    Route::post('/area-profissional/arquivos/documentos-verificacao', [ProfessionalProfileFilesController::class, 'storeVerificationDocuments'])->name('professional.files.verification');
    Route::get('/area-profissional/arquivos/documentos-verificacao/{professional_file}', [ProfessionalProfileFilesController::class, 'showVerificationDocument'])->name('professional.files.verification.show');
    Route::post('/area-profissional/arquivos/fotos-publicas', [ProfessionalProfileFilesController::class, 'storePublicPhotos'])->name('professional.files.public');
    Route::delete('/area-profissional/arquivos/arquivo/{professional_file}', [ProfessionalProfileFilesController::class, 'destroyFile'])->name('professional.files.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';