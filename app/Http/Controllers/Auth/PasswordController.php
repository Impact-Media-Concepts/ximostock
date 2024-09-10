<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Log;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        if (!$request->user()) {
            Log::error("User not authenticated");
            return redirect()->route('login');
        }

        try {
            $validated = $request->validateWithBag('updatePassword', [
                'current_password' => ['required', 'current_password'],
                'password' => ['required', Password::defaults(), 'confirmed'],
            ]);
        } catch (\Exception $e) {
            return back()->with('status', 'validation-failed')->withErrors($e->errors())->withInput();
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        Auth::logout();
        return redirect()->route('login')->with('status', 'password-updated');
    }

    public function view()
    {
        return view('auth.reset-password');
    }
}
