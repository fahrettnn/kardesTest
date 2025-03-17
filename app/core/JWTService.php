<?php

namespace App\Core;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    private $secret_key = "your_secret_key"; // Güvenli bir secret key belirleyin
    private $algorithm = "HS256"; // Şifreleme algoritması
    private $token_lifetime = 3600; // 1 saat
    private $refresh_lifetime = 86400; // 24 saat

    public function generateToken($userId, $email)
    {
        $payload = [
            "iss" => "your_website.com",
            "iat" => time(),
            "exp" => time() + $this->token_lifetime,
            "sub" => $userId,
            "email" => $email
        ];

        return JWT::encode($payload, $this->secret_key, $this->algorithm);
    }

    public function generateRefreshToken($userId)
    {
        $payload = [
            "iss" => "your_website.com",
            "iat" => time(),
            "exp" => time() + $this->refresh_lifetime,
            "sub" => $userId
        ];

        return JWT::encode($payload, $this->secret_key, $this->algorithm);
    }

    public function validateToken($token)
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, $this->algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function refreshAccessToken($refreshToken)
    {
        $decoded = $this->validateToken($refreshToken);
        if (!$decoded) {
            return null;
        }

        return $this->generateToken($decoded['sub'], $decoded['email'] ?? '');
    }
}
