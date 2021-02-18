<?php

namespace App\Auth;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;

/**
 * Handle client authentication
 */
class AuthClient
{
    /**
     * Choose the right grant type form
     *
     * @param String $type password|refresh_token
     * @param Array $credentials User credentials
     * 
     * @return array
     **/
    private function getFormGrantType($grantType, $credentials)
    {
        $formGrant = [
            'grant_type' => 'refresh_token',
            'client_id' => config('oauth.passport_client_id'),
            'client_secret' => config('oauth.passport_client_secret'),
            'scope' => '*',
        ];

        if ($grantType === 'password') {
            $formGrant['grant_type'] = 'password';
            $formGrant = array_merge($formGrant, [
                'username' => $credentials['email'],
                'password' => $credentials['password'],
            ]);
        }

        if ($grantType === 'refresh_token') {
            $formGrant['refresh_token'] = $credentials['refresh_token'];
        }

        return $formGrant;
    }

    /**
     * Set tokens for the given client
     *
     * @param String $type password|refresh_token
     * @param Array $credentials User credentials
     * 
     * @return Illuminate\Http\JsonResponse
     **/
    public function setClientTokens($grantType, $credentials)
    {
        $authorizationForm = $this->getFormGrantType($grantType, $credentials);
        
        try {
            $response = Http::asForm()->post(config('app.url') . '/oauth/token', $authorizationForm);
            $decodedResponse = $response->throw()->object();
        } catch(\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
        
        $accessToken = $decodedResponse->access_token;
        $refreshToken = $decodedResponse->refresh_token;

        if (config('jwt.token_delivery_strategy') == 'cookie') {
            $this->deliveryCookieTokens($accessToken, $refreshToken);
        }

        return response()->json([
            'access_token_ttl' => now()->addSeconds(config('jwt.access_token_ttl') - 30),
            'refresh_token_ttl' => now()->addSeconds(config('jwt.refresh_token_ttl') - 30) 
        ], 200);
    }

    /**
     * Use cookies to return client tokens
     *
     * @param String $accessToken
     * @param String $refreshToken
     * 
     * @return void
     **/
    private function deliveryCookieTokens($accessToken, $refreshToken)
    {
        $accessTokenTTL = config('jwt.access_token_ttl') - 30;
        $refreshTokenTTL = config('jwt.refresh_token_ttl') - 30;

        Cookie::queue('access_token', $accessToken, $accessTokenTTL / 60);
        Cookie::queue('refresh_token', $refreshToken, $refreshTokenTTL / 60);
    }
}
