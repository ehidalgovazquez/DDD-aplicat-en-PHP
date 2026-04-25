<?php

namespace App\Application\Student\UpdateStudent;

use App\Domain\Student\StudentId;
use App\Domain\Course\CourseId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseRepository;

class UpdateStudentHandler {
    public function __construct(
        private readonly StudentRepository $studentRepository,
        private readonly CourseRepository $courseRepository
    ) {}

    public function handle(UpdateStudentCommand $command): void {
        $student = $this->studentRepository->find(new StudentId($command->id));

        if (!$student) {
            throw new \RuntimeException("L'estudiant no existeix.");
        }

        $student->update($command->name, $command->email);

        if (!empty($command->courseId)) {
            $course = $this->courseRepository->find(new CourseId($command->courseId));
            if (!$course) {
                throw new \RuntimeException("El curs seleccionat no existeix.");
            }
            $student->enrollInto($course->id());
        } else {
            $student->unenroll(); 
        }

        $this->studentRepository->update($student);
    }
}