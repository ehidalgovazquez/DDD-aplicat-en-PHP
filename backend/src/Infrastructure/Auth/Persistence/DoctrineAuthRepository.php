<?php

namespace App\Infrastructure\Auth\Persistence;

use App\Domain\Auth\AuthRepository;
use App\Domain\Auth\User;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineAuthRepository implements AuthRepository
{
    public function __construct(private EntityManagerInterface $em) {}

    public function findByGoogleId(string $googleId): ?User
    {
        return $this->em->getRepository(User::class)
            ->findOneBy(['googleId' => $googleId]);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);
    }

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}
