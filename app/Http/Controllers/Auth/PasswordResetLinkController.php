<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordResetRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    public function create(Request $request): Response
    {
        return Inertia::render('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
        ]);
    }

    public function store(PasswordResetRequest $request): RedirectResponse
    {
        if(!User::where('email', $request->email)->first()){
            return back()->withErrors(['email' => 'Este correo no está registrado.']);
        }

        Password::sendResetLink(
            $request->only('email')
        );

        return back()->with('status', __(Password::RESET_LINK_SENT));
    }
}
