<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('advertiser.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:advertisers,email',
            'password'     => 'required|string|min:8|confirmed',
            'phone'        => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'logo'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $dest = public_path('uploads/advertisers/logos');
            if (!file_exists($dest)) mkdir($dest, 0755, true);
            $file->move($dest, $filename);
            $logoPath = 'uploads/advertisers/logos/' . $filename;
        }

        $advertiser = Advertiser::create([
            'name'          => $request->input('name'),
            'email'         => $request->input('email'),
            'password'      => Hash::make($request->input('password')),
            'phone'         => $request->input('phone'),
            'company_name'  => $request->input('company_name'),
            'logo'          => $logoPath,
            'is_active'     => true,
            'trial_ends_at' => now()->addDays(3),
        ]);

        Auth::guard('advertiser')->login($advertiser);

        Log::info('Nouvel annonceur inscrit — essai 3 jours', [
            'advertiser_id' => $advertiser->id,
            'trial_ends_at' => $advertiser->trial_ends_at,
        ]);

        return redirect()->route('advertiser.dashboard')
            ->with('success', 'Bienvenue ! Votre période d\'essai de 3 jours a démarré.');
    }

    public function showLogin()
    {
        return view('advertiser.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('advertiser')->attempt($credentials, $request->boolean('remember'))) {
            return redirect()->back()
                ->withInput(['email' => $request->email])
                ->withErrors(['email' => 'Identifiants incorrects.']);
        }

        return redirect()->route('advertiser.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('advertiser')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('advertiser.login');
    }
}
