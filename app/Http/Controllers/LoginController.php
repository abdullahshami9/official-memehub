<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {

        // $user = Socialite::driver($provider)->user();
        try {
            //code...
            // $SocialUser = Socialite::driver($provider)->stateless()->user();
            $SocialUser = Socialite::driver($provider)->user();
            if (User::where('email', $SocialUser->getEmail())->exists()) {
                // return redirect('/login')->withErrors(['email' => 'This email uses different method to login.']);
                $user = User::where([
                    'email' => $SocialUser->getEmail()
                ])->first();

                Auth::login($user);
                return redirect('/dashboard');
            }

            $user = User::where([
                'provider' => $provider,
                'provider_id' => $SocialUser->id
            ])->first();

            if (!$user) {
                $user = User::create([
                    'name' => $SocialUser->name,
                    'email' => $SocialUser->email,
                    'username' => User::generateUserName($SocialUser->nickname),
                    'provider' => $provider,
                    'provider_id' => $SocialUser->id,
                    'provider_token' => $SocialUser->token,
                    'email_verified_at' => now()
                ]);

                $user = User::updateOrCreate([
                    'provider_id' => $SocialUser->id,
                    'provider' => $provider
                ], [
                    'name' => $SocialUser->name,
                    'username' => User::generateUserName($SocialUser->nickname),
                    'email' => $SocialUser->email,
                    'provider_token' => $SocialUser->token,
                    'provider_refresh_token' => $SocialUser->refreshToken,
                ]);

                Auth::login($user);

                return redirect('/dashboard');
            }
        } catch (\Exception $e) {
            //throw $th;
            return redirect('/login');
        }


    }

    public function userdata(){
        $allUser = User::select()
        ->get();
        return $allUser;
    }
}