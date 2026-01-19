<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {

        $userNumbers = User::get()->pluck('phone_number');
        $request->validate([
            'phone_number' => ['required', 'string', 'numeric', 'digits:10', Rule::in($userNumbers)],
            'password' => ['required', 'string'],
        ], [
            'phone_number.in' => 'The phone number does not exist in our records.',
        ]);

        $user = User::where('phone_number', $request['phone_number'])->first();

        $passwordIsRight =  Hash::check($request['password'], $user['password']);

        if (!$passwordIsRight) {
            $request->validate([
                'password' => [Rule::in(['wrong phone number'])],

            ], [
                'password.in' => 'Wrong password.'
            ]);
        }

        Auth::loginUsingId($user['id']);


        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
