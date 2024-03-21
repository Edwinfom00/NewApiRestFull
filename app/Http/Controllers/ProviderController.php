<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback($provider)
    {
        $user = Socialite::driver($provider)->user();
    }
}
