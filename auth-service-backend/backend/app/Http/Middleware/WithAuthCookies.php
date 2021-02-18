<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WithAuthCookies
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasCookie('access_token')) {
            $accessToken = $request->cookie('access_token');
            $request->headers->set('Authorization', 'Bearer ' . $accessToken);
        }

        return $next($request);
    }
}
