<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helper\jwtToken;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $token = $request->cookie('token');

        if (!$token) {
            return response()->json([
                'message' => 'Invalid token',
                'status' => 'failed',
            ]);
        }
        $payload = jwtToken::verifyToken($token);
        //dd($payload);
        $request->headers->set('user_email', $payload->user_email);
        $request->headers->set('user_id', $payload->user_id ?? null);



        return $next($request);
    }
}
