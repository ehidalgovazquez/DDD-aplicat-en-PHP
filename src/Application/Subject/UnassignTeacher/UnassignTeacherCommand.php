<?php

namespace App\Application\Subject\UnassignTeacher;

final class UnassignTeacherCommand {
    public function __construct(
        public readonly string $subjectId
    ) {}
}