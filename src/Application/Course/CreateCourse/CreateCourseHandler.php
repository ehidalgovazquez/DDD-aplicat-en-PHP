<?php

namespace App\Application\Course\CreateCourse;

use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use App\Application\Course\CreateCourse\CreateCourseCommand;

class CreateCourseHandler {
    public function __construct(
        public readonly CourseRepository $courseRepository
    ) {}

    public function handle(CreateCourseCommand $command): void {
        $courseId = new CourseId($command->id);

        if ($this->courseRepository->find($courseId) !== null) {
            throw new \RuntimeException("El curs amb ID '" . $command->id . "' ja existeix.");
        }

        $course = new Course($courseId, $command->name);
        $this->courseRepository->save($course);
    }
}