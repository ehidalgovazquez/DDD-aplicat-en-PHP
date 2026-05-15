<?php

namespace App\Domain\Auth;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'auth_users')]
final class User
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 100)]
    private string $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(name: 'google_id', type: 'string', unique: true)]
    private string $googleId;

    public function __construct(UserId $id, string $email, string $name, string $googleId)
    {
        $this->id       = $id->value();
        $this->email    = $email;
        $this->name     = $name;
        $this->googleId = $googleId;
    }

    public function id(): UserId
    {
        return new UserId($this->id);
    }

    public function email(): string
    {
        return $this->email;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function googleId(): string
    {
        return $this->googleId;
    }
}
