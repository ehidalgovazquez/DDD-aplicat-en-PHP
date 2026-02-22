<?php

namespace App\Application\Student;

use App\Domain\Student\StudentId;
use App\Domain\Course\CourseId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseRepository;
use App\Application\Student\EnrollStudent\EnrollStudentCommand;

class EnrollStudentHandler {
    public function __construct(
        public readonly StudentRepository $studentRepository,
        public readonly CourseRepository $courseRepository
    ) {}

    public function handle(EnrollStudentCommand $command): void
    {
        $student = $this->studentRepository->find(new StudentId($command->studentId));
        if (!$student) {
            throw new \RuntimeException('Student not found');
        }

        $course = $this->courseRepository->find(new CourseId($command->courseId));
        if (!$course) {
            throw new \RuntimeException('Course not found');
        }

        $student->enrollInto($course->id());

        $this->studentRepository->save($student);
    }
}