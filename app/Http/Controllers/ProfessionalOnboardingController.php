<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfessionalOnboardingRequest;
use App\Repositories\ProfessionRepository;
use App\Repositories\ProfessionalRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfessionalOnboardingController extends Controller
{
    public function __construct(
        private readonly ProfessionRepository $professionRepository,
        private readonly ProfessionalRepository $professionalRepository,
    ) {}

    public function edit(Request $request): RedirectResponse|View
    {
        $user = $request->user();
        if (! $user->isProfessional() || $user->professionals()->exists()) {
            return redirect()->route('dashboard');
        }

        return view('professional.setup', [
            'professions' => $this->professionRepository->orderedForProfessionalsFilter(),
        ]);
    }

    public function store(ProfessionalOnboardingRequest $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->professionals()->exists()) {
            return redirect()->route('dashboard');
        }

        $data = $request->validated();

        DB::transaction(function () use ($user, $data) {
            $this->professionalRepository->create([
                'user_id' => $user->id,
                'profession_id' => (int) $data['profession_id'],
                'rg' => $data['rg'],
                'cpf' => $data['cpf'],
                'cnpj' => $data['cnpj'],
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'hourly_rate_cents' => (int) round((float) $data['hourly_rate_reais'] * 100),
            ]);

            if (! empty($data['password'])) {
                $user->password = Hash::make($data['password']);
                $user->save();
            }
        });

        return redirect()
            ->route('dashboard')
            ->with('status', 'professional-onboarding-complete');
    }
}
