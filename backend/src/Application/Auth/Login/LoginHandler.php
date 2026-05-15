<?php

namespace App\Application\Auth\Login;

use App\Domain\Auth\AuthRepository;
use App\Domain\Auth\User;
use App\Domain\Auth\UserId;
use App\Infrastructure\Auth\Token\JwtTokenGenerator;

final class LoginHandler
{
    public function __construct(
        private AuthRepository $repository,
        private JwtTokenGenerator $tokenGenerator
    ) {}

    /**
     * Finds an existing user by Google ID or creates a new one.
     * Returns a signed JWT for the user.
     */
    public function handle(LoginCommand $command): string
    {
        $user = $this->repository->findByGoogleId($command->googleId);

        if ($user === null) {
            $user = new User(
                UserId::generate(),
                $command->email,
                $command->name,
                $command->googleId
            );
            $this->repository->save($user);
        }

        return $this->tokenGenerator->generate($user);
    }
}
