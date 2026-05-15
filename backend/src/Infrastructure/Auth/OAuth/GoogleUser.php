<?php

namespace App\Infrastructure\Auth\OAuth;

/**
 * DTO that holds the profile data returned by Google after a successful OAuth callback.
 */
final class GoogleUser
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $name
    ) {}
}
