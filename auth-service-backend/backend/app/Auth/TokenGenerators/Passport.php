<?php

namespace App\Auth\TokenGenerators;

use Illuminate\Support\Facades\Http;
use App\Auth\Interfaces\TokenGenerator;
use Exception;

class Passport implements TokenGenerator
{
    private $grantType;
    private $credentials;

    public function __construct($grantType, $credentials)
    {
        $this->grantType = $grantType;
        $this->credentials = $credentials;
    }

    /**
     * Choose the right grant type form
     *
     * @param String $type password|refresh_token
     * @param Array $credentials User credentials
     * 
     * @return array
     **/
    private function getFormGrantType()
    {
        $formGrant = [
            'grant_type' => 'refresh_token',
            'client_id' => config('oauth.passport_client_id'),
            'client_secret' => config('oauth.passport_client_secret'),
            'scope' => '*',
        ];

        if ($this->grantType === 'password') {
            $formGrant['grant_type'] = 'password';
            $formGrant = array_merge($formGrant, [
                'username' => $this->credentials['email'],
                'password' => $this->credentials['password'],
            ]);
        }

        if ($this->grantType === 'refresh_token') {
            $formGrant['refresh_token'] = $this->credentials['refresh_token'];
        }

        return $formGrant;
    }

    public function generateTokens()
    {
        $authorizationForm = $this->getFormGrantType($this->grantType, $this->credentials);
        
        try {
            $response = Http::asForm()->post(config('app.url') . '/oauth/token', $authorizationForm);
            $decodedResponse = $response->throw()->object();
        } catch(\Exception $e) {
            return response()->json(['error' => ''], 500);
        }

        return [
            'accessToken' => $decodedResponse->access_token,
            'refreshToken' => $decodedResponse->refresh_token,
        ];
    }
}