<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $financeSummary = null;
        $user = auth()->user();
        if ($user !== null && $user->isProfessional()) {
            $financeSummary = Service::financeSummaryForProfessionalUser($user->id);
        }

        return view('dashboard', [
            'financeSummary' => $financeSummary,
        ]);
    }
}
