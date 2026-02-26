<?php

namespace App\Application\Subject\AssignTeacher;

final class AssignTeacherCommand {
    public function __construct(
        public readonly string $subjectId,
        public readonly string $teacherId
    ) {}
}