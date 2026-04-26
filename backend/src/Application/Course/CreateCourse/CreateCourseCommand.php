<?php

namespace App\Application\Course\CreateCourse;

final class CreateCourseCommand {
    public function __construct(
        public readonly string $id,
        public readonly string $name
    ) {}
}