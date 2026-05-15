<?php

namespace App\Domain\Auth;

interface AuthRepository
{
    public function findByGoogleId(string $googleId): ?User;

    public function findByEmail(string $email): ?User;

    public function save(User $user): void;
}
