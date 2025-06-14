<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class jwtToken
{

    public static function createToken($user_email, $user_id)
    {
        $key = env('JWT_TOKEN');
        $payload = [
            'iss' => 'example',
            'user_id' => $user_id,
            'user_email' => $user_email,
            'exp' => time() + 3600 * 24,
            'iat' => time(),
        ];
        $token = JWT::encode($payload, $key, 'HS256');
        return $token;
    }
    public static function verifyToken($token)
    {
        try {
            if (!$token) {
                return response()->json(
                    [

                        'message' => 'invalid token',
                        'status' => 'failed',
                    ]
                );
            }
            $key = env('JWT_TOKEN');

            $payload = JWT::decode($token, new Key($key, 'HS256'));
            return $payload;
        } catch (\Throwable $e) {
            return 'invalid token';
        }
    }
    public static function createTokenForPasswordReset($user_email)
    {
        $key = env('JWT_TOKEN');

        $payload = [
            'iss' => 'example',
            'user_email' => $user_email,
            'exp' => time() + 3600 * 24,
            'iat' => time(),
        ];
        $token = JWT::encode($payload, $key, 'HS256');

        return $token;
    }
}
