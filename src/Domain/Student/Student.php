<?php

namespace App\Domain\Student;

use App\Domain\Course\CourseId;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'students')]
final class Student {
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 36, nullable: true)]
    private ?CourseId $courseId = null;

    public function __construct(StudentId $id, string $name, string $email) {
        $this->id = $id->value();
        $this->name = $name;
        $this->email = $email;
    }

    public function enrollInto(CourseId $courseId): void {
        $this->courseId = $courseId;
    }

    public function unenroll(): void {
        $this->courseId = null;
    }

    public function id(): StudentId {
        return new StudentId($this->id);
    }

    public function name(): string {
        return $this->name;
    }

    public function email(): string {
        return $this->email;
    }
    
    public function courseId(): ?CourseId { 
        return $this->courseId; 
    }

    public function update(string $name, string $email): void {
        $this->name = $name;
        $this->email = $email;
    }
}