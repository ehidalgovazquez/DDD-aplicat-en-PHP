<?php

namespace App\Application\Auth\Login;

final class LoginCommand
{
    public function __construct(
        public readonly string $googleId,
        public readonly string $email,
        public readonly string $name
    ) {}
}
