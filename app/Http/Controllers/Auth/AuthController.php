<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if (! $user->status) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Your account has been deactivated.',
            ])->onlyInput('email');
        }

        if (! $user->is_super_admin && $user->pharmacy && ! $user->pharmacy->status) {
            Auth::logout();

            return back()->withErrors([
                'email' => 'This pharmacy account has been suspended.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($user->is_super_admin) {
            return redirect()->intended(route('super-admin.pharmacies.index'));
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
