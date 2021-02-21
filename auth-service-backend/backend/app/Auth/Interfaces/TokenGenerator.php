<?php

namespace App\Auth\Interfaces;

interface TokenGenerator 
{
    /**
    * Generate tokens
    * 
    * @return array
    * 
    * @throws \Exception
     **/
    public function generateTokens();
}