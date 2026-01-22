<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helpers\CelcomHelper;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $userPhoneNumbers = User::get()->pluck('phone_number');
        // dd($userPhoneNumbers);
        $request->validate([
            'phone_number' => ['required', 'string', 'digits:10', Rule::in($userPhoneNumbers)],
        ], [
            'phone_number.in' => 'The phone number is invalid.'
        ]);


        $user  = User::where('phone_number',  $request->phone_number)->first();
        $resetCode = fake()->randomNumber(5);

        $user->update([
            'remember_token' => $resetCode,
        ]);


        $appName = config('app.name');

        $message = "Dear {$user['name']}, your password reset code is {$resetCode}.
$appName in TZ";


        CelcomHelper::sendMessage($user->phone_number, $message);


        return redirect('/reset-password')->with([
            'status' => 'Your password reset code has been sent to ' . $request->phone_number
        ]);
    }
}
