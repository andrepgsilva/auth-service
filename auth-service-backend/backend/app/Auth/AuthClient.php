<?php

namespace App\Auth;

use TokenDeliveryFactory;
use App\Auth\Interfaces\TokenGenerator;
use App\Auth\TokenGenerators\Passport;

/**
 * Handle client authentication
 */
class AuthClient
{
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
        $tokenHandler = new Passport($grantType, $credentials);
        $tokens = $this->getTokensForClient($tokenHandler);
        
        $this->deliveryTokens($tokens['access_token'], $tokens['refresh_token']);

        return response()->json([
            'access_token_ttl' => now()->addSeconds(config('jwt.access_token_ttl') - 30),
            'refresh_token_ttl' => now()->addSeconds(config('jwt.refresh_token_ttl') - 30) 
        ], 200);
    }

    /**
     * Get tokens for client using a token generator
     *
     * @return array
     **/
    private function getTokensForClient(TokenGenerator $tokenHandler)
    {
        return $tokenHandler->generateTokens();         
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
}
