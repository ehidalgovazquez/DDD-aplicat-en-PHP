<?php

namespace App\Application\Teacher\UpdateTeacher;

use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;

class UpdateTeacherHandler {
    public function __construct(private readonly TeacherRepository $teacherRepository) {}

    public function handle(UpdateTeacherCommand $command): void {
        $teacher = $this->teacherRepository->find(new TeacherId($command->id));

        if (!$teacher) {
            throw new \RuntimeException("El professor no existeix.");
        }

        $teacher->update($command->name, $command->email);

        $this->teacherRepository->update($teacher);
    }
}