<?php

namespace App\Auth\Interfaces;

/**
 * Token delivery interface
 */
interface TokenDelivery
{
    /**
     * Delivery tokens method
     *
     * @param String $accessToken
     * @param String $refreshToken
     * 
     * @return mixed
     **/
    public function deliveryTokens($accessToken, $refreshToken);
}
