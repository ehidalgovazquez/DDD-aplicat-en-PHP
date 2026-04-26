<?php

namespace App\Application\Teacher\DeleteTeacher;

use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;

class DeleteTeacherHandler {
    public function __construct(private readonly TeacherRepository $teacherRepository) {}

    public function handle(DeleteTeacherCommand $command): void {
        $teacherId = new TeacherId($command->id);

        if (!$this->teacherRepository->find($teacherId)) {
            throw new \RuntimeException("No es pot eliminar: el professor no existeix.");
        }

        $this->teacherRepository->delete($teacherId);
    }
}