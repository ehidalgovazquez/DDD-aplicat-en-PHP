<?php

namespace App\Application\Student\UnenrollStudent;

final class UnenrollStudentCommand {
    public function __construct(
        public readonly string $studentId
    ) {}
}