<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\School;
use App\Providers\AppServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function login(): Response
    {
        return Inertia::render('Auth/Login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function storeLogin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(AppServiceProvider::HOME);
    }

    /**
     * Display the register view.
     */
    public function register(): Response
    {
        $schools = School::all();
        return Inertia::render('Auth/Register', [
            'schools' => $schools, // Envíalo al componente Vue
        ]);
    }

    /**
     * Handle an incoming register authentication request.
     */
    public function storeRegister(RegisterRequest $request)
    {
        $request->register();

        $request->session()->regenerate();

        return Inertia::location(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Inertia::location('/');
    }
}
