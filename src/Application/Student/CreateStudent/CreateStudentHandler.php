<?php
namespace App\Application\Student;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use App\Application\Student\CreateStudent\CreateStudentCommand;

class CreateStudentHandler {
    public function __construct(
        public readonly StudentRepository $studentRepository
    ) {}

    public function handle(CreateStudentCommand $command): void
    {
        $student = new Student(
            new StudentId($command->id),
            $command->name,
            $command->email
        );

        $this->studentRepository->save($student);
    }
}