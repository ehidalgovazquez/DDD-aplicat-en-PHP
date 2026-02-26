<?php

namespace App\Domain\Course;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'courses')]
final class Course {
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    public function __construct(CourseId $id, string $name) {
        $this->id = $id->value();
        $this->name = $name;
    }

    public function id(): CourseId {
        return new CourseId($this->id);
    }

    public function name(): string {
        return $this->name;
    }

    public function update(string $name): void {
        $this->name = $name;
    }
}