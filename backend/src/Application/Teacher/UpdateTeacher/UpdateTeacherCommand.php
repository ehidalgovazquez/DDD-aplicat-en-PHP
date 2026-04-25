<?php

namespace App\Application\Teacher\UpdateTeacher;

final class UpdateTeacherCommand {
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email
    ) {}
}