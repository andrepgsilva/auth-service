<?php

namespace App\Auth;

use Illuminate\Support\Facades\Http;
use TokenDeliveryFactory;

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
     * Delivery tokens for the client
     *
     * @param String $accessToken
     * @param String $deliveryToken
     * 
     * @return void
     **/
    private function deliveryTokens($accessToken, $refreshToken)
    {
        $deliveryStrategyConfig = config('jwt.token_delivery_strategy');

        $deliveryStrategy = TokenDeliveryFactory::getTokenDeliveryMethod(
            $deliveryStrategyConfig
        );
        
        $deliveryStrategy->deliveryTokens($accessToken, $refreshToken);
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
        
        $this->deliveryTokens($decodedResponse->access_token, $decodedResponse->refresh_token);

        return response()->json([
            'access_token_ttl' => now()->addSeconds(config('jwt.access_token_ttl') - 30),
            'refresh_token_ttl' => now()->addSeconds(config('jwt.refresh_token_ttl') - 30) 
        ], 200);
    }
}
