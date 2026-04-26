<?php

namespace App\Application\Student\DeleteStudent;

use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;

class DeleteStudentHandler {
    public function __construct(
        private readonly StudentRepository $studentRepository
    ) {}

    public function handle(DeleteStudentCommand $command): void {
        $studentId = new StudentId($command->id);
        
        $student = $this->studentRepository->find($studentId);
        if (!$student) {
            throw new \RuntimeException("No es pot eliminar: l'estudiant no existeix.");
        }

        $this->studentRepository->delete($studentId);
    }
}