<?php

namespace App\Application\Course\DeleteCourse;

final class DeleteCourseCommand {
    public function __construct(public readonly string $id) {}
}