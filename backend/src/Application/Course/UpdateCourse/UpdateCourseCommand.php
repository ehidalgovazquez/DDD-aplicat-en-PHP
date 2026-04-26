<?php

namespace App\Application\Course\UpdateCourse;

final class UpdateCourseCommand {
    public function __construct(
        public readonly string $id,
        public readonly string $name
    ) {}
}