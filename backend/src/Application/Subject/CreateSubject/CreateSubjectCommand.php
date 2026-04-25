<?php

namespace App\Application\Subject\CreateSubject;

final class CreateSubjectCommand {
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $teacherId = null
    ) {}
}