<?php

namespace App\Application\Student\UpdateStudent;

final class UpdateStudentCommand {
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $courseId
    ) {}
}