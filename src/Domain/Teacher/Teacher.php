<?php

namespace App\Domain\Teacher;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'teachers')]
final class Teacher
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    public function __construct(TeacherId $id, string $name, string $email)
    {
        $this->id = $id->value();
        $this->name = $name;
        $this->email = $email;
    }

    public function id(): TeacherId
    {
        return new TeacherId($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }
}