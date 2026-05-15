<?php

namespace App\Infrastructure\Auth\Token;

/**
 * Validates an incoming JWT (HS256) and returns its payload.
 */
final class TokenValidator
{
    public function __construct(private string $secret) {}

    /**
     * @return array<string, mixed>  Decoded payload.
     * @throws \RuntimeException     If the token is malformed, signature invalid, or expired.
     */
    public function validate(string $token): array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            throw new \RuntimeException('Malformed JWT');
        }

        [$header, $payload, $signature] = $parts;

        $expectedSignature = $this->base64url(
            hash_hmac('sha256', $header . '.' . $payload, $this->secret, true)
        );

        if (!hash_equals($expectedSignature, $signature)) {
            throw new \RuntimeException('Invalid JWT signature');
        }

        $claims = json_decode($this->base64urlDecode($payload), true);

        if (isset($claims['exp']) && $claims['exp'] < time()) {
            throw new \RuntimeException('JWT token has expired');
        }

        return $claims;
    }

    private function base64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64urlDecode(string $data): string
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }
}
