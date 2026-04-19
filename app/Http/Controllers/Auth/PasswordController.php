<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Alteração de senha na área logada (confirma senha atual).
 */
class PasswordController extends Controller
{
    /**
     * Atualiza a senha após validar senha atual e confirmação.
     */
    public function update(Request $requisicao): RedirectResponse
    {
        $validado = $requisicao->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $requisicao->user()->update([
            'password' => Hash::make($validado['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
