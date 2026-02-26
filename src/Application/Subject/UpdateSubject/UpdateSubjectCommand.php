<?php

namespace App\Application\Subject\UpdateSubject;

final class UpdateSubjectCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $teacherId = null
    ) {}
}