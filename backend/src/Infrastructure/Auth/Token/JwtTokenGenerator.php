<?php

namespace App\Infrastructure\Auth\Token;

use App\Domain\Auth\User;

/**
 * Generates and signs JWT tokens using HS256 (HMAC-SHA256).
 *
 * No external library is needed — this is a minimal, self-contained
 * implementation that follows the JWT spec (RFC 7519).
 */
final class JwtTokenGenerator
{
    private string $secret;
    private int $ttlSeconds;

    public function __construct(string $secret, int $ttlSeconds = 3600)
    {
        $this->secret     = $secret;
        $this->ttlSeconds = $ttlSeconds;
    }

    public function generate(User $user): string
    {
        $header = $this->base64url(json_encode([
            'alg' => 'HS256',
            'typ' => 'JWT',
        ]));

        $payload = $this->base64url(json_encode([
            'sub'   => $user->id()->value(),
            'email' => $user->email(),
            'name'  => $user->name(),
            'iat'   => time(),
            'exp'   => time() + $this->ttlSeconds,
        ]));

        $signature = $this->base64url(
            hash_hmac('sha256', $header . '.' . $payload, $this->secret, true)
        );

        return $header . '.' . $payload . '.' . $signature;
    }

    private function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
