<?php

namespace App\Http\Controllers\Reader;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if ($this->isAuthenticated()) return redirect(request()->getSchemeAndHttpHost() . '/reader');
        return view('reader.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $creds    = $request->only('email', 'password');
        $remember = $request->boolean('remember');
        $base     = $request->getSchemeAndHttpHost();

        if (Auth::guard('reader')->attempt($creds, $remember))    return redirect($base . '/reader');
        if (Auth::guard('web')->attempt($creds, $remember))       return redirect($base . '/reader');
        if (Auth::guard('advertiser')->attempt($creds, $remember))return redirect($base . '/reader');
        if (Auth::guard('admin')->attempt($creds, $remember))     return redirect($base . '/reader');

        return back()->withInput(['email' => $request->email])
            ->withErrors(['email' => 'Email ou mot de passe incorrect.']);
    }

    public function showRegister()
    {
        if ($this->isAuthenticated()) return redirect(request()->getSchemeAndHttpHost() . '/reader');
        return view('reader.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:readers,email',
            'password'  => 'required|string|min:8|confirmed',
            'newsletter'=> 'accepted',
        ], [
            'newsletter.accepted' => 'Vous devez vous abonner à la newsletter pour créer un compte lecteur.',
        ]);

        $reader = Reader::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Subscribe to newsletter
        NewsletterSubscriber::firstOrCreate(
            ['email' => $request->email],
            ['name'  => $request->name, 'is_active' => true]
        );

        Auth::guard('reader')->login($reader);

        return redirect(request()->getSchemeAndHttpHost() . '/reader')
            ->with('success', 'Bienvenue ' . $reader->name . ' !');
    }

    public function logout(Request $request)
    {
        Auth::guard('reader')->logout();
        Auth::guard('web')->logout();
        Auth::guard('advertiser')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(request()->getSchemeAndHttpHost() . '/reader/login');
    }

    private function isAuthenticated(): bool
    {
        return Auth::guard('reader')->check() ||
               Auth::guard('web')->check() ||
               Auth::guard('advertiser')->check() ||
               Auth::guard('admin')->check();
    }
}
