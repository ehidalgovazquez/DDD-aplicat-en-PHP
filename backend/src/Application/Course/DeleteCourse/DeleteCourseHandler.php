<?php

namespace App\Application\Course\DeleteCourse;

use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;

class DeleteCourseHandler {
    public function __construct(private readonly CourseRepository $courseRepository) {}

    public function handle(DeleteCourseCommand $command): void {
        $courseId = new CourseId($command->id);

        if (!$this->courseRepository->find($courseId)) {
            throw new \RuntimeException("No es pot eliminar un curs que no existeix.");
        }

        $this->courseRepository->delete($courseId);
    }
}