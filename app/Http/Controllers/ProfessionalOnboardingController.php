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
        if (! $user || ! $user->isProfessional()) {
            return redirect()->route('dashboard');
        }

        return view('professional.setup', [
            'professions' => $this->professionRepository->orderedForProfessionalsFilter(),
            'professional' => $user->professionals()->first(),
        ]);
    }

    public function store(ProfessionalOnboardingRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $payload = [
            'profession_id' => (int) $data['profession_id'],
            'rg' => $data['rg'],
            'cpf' => $data['cpf'],
            'cnpj' => $data['cnpj'],
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'hourly_rate_cents' => (int) round((float) $data['hourly_rate_reais'] * 100),
        ];

        $existing = $user->professionals()->first();

        DB::transaction(function () use ($user, $data, $payload, $existing) {
            if ($existing !== null) {
                $this->professionalRepository->update($existing, $payload);
            } else {
                $this->professionalRepository->create(array_merge(
                    ['user_id' => $user->id],
                    $payload
                ));
            }

            if (! empty($data['password'])) {
                $user->password = Hash::make($data['password']);
                $user->save();
            }
        });

        $status = $existing !== null
            ? 'professional-profile-updated'
            : 'professional-onboarding-complete';

        return redirect()
            ->route('dashboard')
            ->with('status', $status);
    }
}
