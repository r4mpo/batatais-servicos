<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfessionalServiceHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        abort_unless($user !== null && $user->isProfessional(), 403);

        $services = Service::query()
            ->with('contractor:id,name,email')
            ->where('professional_user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('professional.service-history', [
            'services' => $services,
        ]);
    }
}
