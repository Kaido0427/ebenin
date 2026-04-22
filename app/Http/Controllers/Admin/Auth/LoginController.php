<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create()
    {
        return view('admin.auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin || !$admin->is_active) {
            return back()->withErrors([
                'email' => 'Acces admin indisponible pour ce compte.',
            ])->withInput();
        }

        if (!Auth::guard('admin')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], (bool) ($credentials['remember'] ?? false))) {
            return back()->withErrors([
                'email' => 'Identifiants admin incorrects.',
            ])->withInput();
        }

        $request->session()->regenerate();

        $admin->forceFill([
            'last_login_at' => now(),
        ])->save();

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
