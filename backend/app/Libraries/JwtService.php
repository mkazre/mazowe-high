<?php

namespace App\Libraries;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    private string $secret;
    private int $accessTtl  = 900;     // 15 minutes
    private int $refreshTtl = 2592000; // 30 days

    public function __construct()
    {
        $this->secret = env('JWT_SECRET') ?: 'mazowe-dev-secret-change-me';
    }

    public function issueAccessToken(int $userId, array $extra = []): string
    {
        $now = time();
        $payload = array_merge([
            'sub' => $userId,
            'iat' => $now,
            'exp' => $now + $this->accessTtl,
            'typ' => 'access',
        ], $extra);

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function issueRefreshToken(int $userId): string
    {
        $now = time();
        $payload = [
            'sub' => $userId,
            'iat' => $now,
            'exp' => $now + $this->refreshTtl,
            'typ' => 'refresh',
        ];

        return JWT::encode($payload, $this->secret, 'HS256');
    }

    public function validate(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret, 'HS256'));

            return (array) $decoded;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
