<?php
namespace App\Application\Student\CreateStudent;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;

class CreateStudentHandler {
    public function __construct(
        private readonly StudentRepository $studentRepository,
        private readonly CourseRepository $courseRepository
    ) {}

    public function handle(CreateStudentCommand $command): void {
        $studentId = new StudentId($command->id);

        if ($this->studentRepository->find($studentId) !== null) {
            throw new \RuntimeException("L'estudiant amb ID '" . $command->id . "' ja existeix.");
        }

        $student = new Student($studentId, $command->name, $command->email);

        if ($command->courseId !== null && $command->courseId !== "") {
            $courseId = new CourseId($command->courseId);
            if ($this->courseRepository->find($courseId) === null) {
                throw new \RuntimeException("El curs amb ID '" . $command->courseId . "' no existeix.");
            }
            
            $student->enrollInto($courseId);
        }

        $this->studentRepository->save($student);
    }
}