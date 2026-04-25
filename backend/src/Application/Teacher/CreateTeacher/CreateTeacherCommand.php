<?php

namespace App\Application\Teacher\CreateTeacher;

final class CreateTeacherCommand {
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $email
    ) {}
}