<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /** 
     * Get the post-login redirect path. 
     *
     * @return string
     */
    protected function redirectTo()
    {
        // Ajout des logs pour le débogage
        $user = auth()->user();
        Log::info('User logged in', ['user_id' => $user->id]);

        $updatedAt = $user->updated_at;
        if (now()->greaterThan($updatedAt)) {
            return redirect()->route('subscription');
        }
        
        $organization = $user->organization;
        Log::info('Organization retrieved', ['organization_id' => $organization->id, 'organization_name' => $organization->organization_name]);

        $subdomain = $organization->subdomain;
        Log::info('Subdomain retrieved', ['subdomain' => $subdomain]);

        $redirectUrl = "https://{$subdomain}.e-benin.com/dashboard";
        Log::info('Redirecting to', ['redirect_url' => $redirectUrl]);

        return $redirectUrl;
    }



}
