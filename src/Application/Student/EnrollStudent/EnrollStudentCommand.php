<?php

namespace App\Application\Student\EnrollStudent;

final class EnrollStudentCommand
{
    public function __construct(
        public readonly string $studentId,
        public readonly string $courseId
    ) {
    }
}