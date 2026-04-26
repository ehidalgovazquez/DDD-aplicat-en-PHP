<?php

namespace App\Application\Teacher\CreateTeacher;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;
use App\Application\Teacher\CreateTeacher\CreateTeacherCommand;

class CreateTeacherHandler {
    public function __construct(
        public readonly TeacherRepository $teacherRepository
    ) {}

    public function handle(CreateTeacherCommand $command): void 
    {
        $existingTeacher = $this->teacherRepository->find(new TeacherId($command->id));

        if ($existingTeacher !== null) {
            throw new \RuntimeException("El professor amb ID '{$command->id}' ja existeix.");
        }

        $teacher = new Teacher(
            new TeacherId($command->id),
            $command->name,
            $command->email
        );

        $this->teacherRepository->save($teacher);
    }
}