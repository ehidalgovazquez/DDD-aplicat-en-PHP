<?php

namespace App\Application\Course;

use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use App\Application\Course\CreateCourse\CreateCourseCommand;

class CreateCourseHandler {
    public function __construct(
        public readonly CourseRepository $courseRepository
    ) {}

    public function handle(CreateCourseCommand $command): void
    {
        $course = new Course(
            new CourseId($command->id),
            $command->name
        );

        $this->courseRepository->save($course);
    }
}