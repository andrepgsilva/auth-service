<?php

namespace App\Auth\Strategies;

use Illuminate\Support\Facades\Cookie;
use App\Auth\Interfaces\TokenDelivery;

class CookieTokensDelivery implements TokenDelivery
{
    public function deliveryTokens($accessToken, $refreshToken) 
    {
        $accessTokenTTL = config('jwt.access_token_ttl') - 30;
        $refreshTokenTTL = config('jwt.refresh_token_ttl') - 30;

        Cookie::queue('access_token', $accessToken, $accessTokenTTL / 60);
        Cookie::queue('refresh_token', $refreshToken, $refreshTokenTTL / 60);
    }
}