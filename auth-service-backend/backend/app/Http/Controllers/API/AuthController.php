<?php

namespace App\Http\Controllers\API;

use App\Auth\AuthClient;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;


class AuthController extends Controller
{
    /**
     * Set tokens for the given client
     *
     * @param App\Auth\AuthClient $authClient
     * @param Illuminate\Http\JsonResponse
     * 
     * @return Illuminate\Http\JsonResponse;
     **/
    public function login(AuthClient $authClient)
    {
        $userCredentials = request()->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string'
        ]);

        $serverResponse = $authClient->setClientTokens('password', $userCredentials);

        return $serverResponse;
    }

    /**
     * Refresh token for the given client
     *
     * @param App\Auth\AuthClient $authClient
     * @param Illuminate\Http\JsonResponse
     * 
     * @return Illuminate\Http\JsonResponse;
     **/
    public function refreshToken(AuthClient $authClient)
    {
        if (! request()->hasCookie('refresh_token')) {
            return response()->json(['error' => 'Invalid refresh token'], 401);
        }

        $clientCredentials = ['refresh_token' => request()->cookie('refresh_token')];
        $serverResponse = $authClient->setClientTokens('refresh_token', $clientCredentials);

        return $serverResponse;
    }
}
