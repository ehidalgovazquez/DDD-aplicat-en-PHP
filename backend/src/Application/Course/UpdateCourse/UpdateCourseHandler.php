<?php

namespace App\Application\Course\UpdateCourse;

use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;

class UpdateCourseHandler {
    public function __construct(private readonly CourseRepository $courseRepository) {}

    public function handle(UpdateCourseCommand $command): void {
        $course = $this->courseRepository->find(new CourseId($command->id));

        if (!$course) {
            throw new \RuntimeException("El curs no existeix.");
        }

        $course->update($command->name);
        $this->courseRepository->update($course);
    }
}