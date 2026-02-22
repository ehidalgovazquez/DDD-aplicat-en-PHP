<?php

namespace App\Application\Student;

use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use App\Application\Student\UnenrollStudent\UnenrollStudentCommand;

final class UnenrollStudentHandler
{
    public function __construct(
        private StudentRepository $studentRepository,
    ) {}

    public function handle(UnenrollStudentCommand $command): void
    {
        $student = $this->studentRepository->find(new StudentId($command->studentId));
        if (!$student) {
            throw new \RuntimeException('Student not found');
        }

        $student->unenroll();

        $this->studentRepository->save($student);
    }
}