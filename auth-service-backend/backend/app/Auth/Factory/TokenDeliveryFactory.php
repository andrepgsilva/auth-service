<?php

namespace App\Auth\Factory;

use App\Auth\Strategies\CookieTokensDelivery;
use App\Auth\Interfaces\TokenDelivery;

class TokenDeliveryFactory
{
    /**
     * Get a payment method by its ID.
     *
     * @param String $strategyName
     * 
     * @return TokenDelivery
     * @throws \Exception
     */
    public static function getTokenDeliveryMethod($strategyName)
    {
        switch ($strategyName) {
            case "cookie":
                return new CookieTokensDelivery();
            default:
                throw new \Exception("Unknown token delivery method");
        }
    }
}