<?php

namespace App\Application\Student\CreateStudent;

final class CreateStudentCommand {
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $courseId = null
    ) {
    }
}