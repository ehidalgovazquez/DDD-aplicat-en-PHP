<?php

namespace App\Domain\Subject;

use App\Domain\Teacher\TeacherId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'subjects')]
final class Subject
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string', length: 36, nullable: true)]
    private ?string $teacherId = null;

    public function __construct(SubjectId $id, string $name) {
        $this->id = $id->value();
        $this->name = $name;
    }

    public function assignTeacher(TeacherId $teacherId): void {
        $this->teacherId = $teacherId->value();
    }

    public function unassignTeacher(): void {
        $this->teacherId = null;
    }

    public function id(): SubjectId {
        return new SubjectId($this->id);
    }

    public function name(): string {
        return $this->name;
    }

    public function teacherId(): ?TeacherId { 
        return $this->teacherId ? new TeacherId($this->teacherId) : null; 
    }

    public function update(string $name): void {
        $this->name = $name;
    }
}