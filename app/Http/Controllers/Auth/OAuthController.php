<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{
    public function redirect(string $provider)
    {
        abort_unless(in_array($provider, ['google', 'github', 'discord']), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        abort_unless(in_array($provider, ['google', 'github', 'discord']), 404);

        $socialUser = Socialite::driver($provider)->user();

        $user = User::updateOrCreate(
            ['oauth_provider_id' => $socialUser->getId(), 'oauth_provider' => $provider],
            [
                'name'            => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Utilisateur',
                'email'           => $socialUser->getEmail(),
                'oauth_avatar'    => $socialUser->getAvatar(),
                'email_verified_at' => now(),
            ]
        );

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}
