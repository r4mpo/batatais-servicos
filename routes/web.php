<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\ProfessionalOnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureProfessionalRegistrationComplete;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/profissionais', [ProfessionalController::class, 'index'])->name('professionals.index');

Route::middleware([
    'auth',
    'verified',
    EnsureProfessionalRegistrationComplete::class,
])->group(function () {
    Route::get('/area-profissional/cadastro', [ProfessionalOnboardingController::class, 'edit'])
        ->name('professional.setup');
    Route::post('/area-profissional/cadastro', [ProfessionalOnboardingController::class, 'store'])
        ->name('professional.setup.store');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
