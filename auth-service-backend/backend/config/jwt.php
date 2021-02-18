<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tokens Lifetime
    |--------------------------------------------------------------------------
    |
    | Here are the jwt lifetimes.
    |
    | The time need to be defined in seconds.
    | TTL's need to be greater than 60
    */
    
    'access_token_ttl'=> 10000,
    'refresh_token_ttl' => 15000,
    'token_delivery_strategy' => 'cookie'
];