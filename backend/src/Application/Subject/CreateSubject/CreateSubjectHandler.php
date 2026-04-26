<?php

namespace App\Application\Subject\CreateSubject;

use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;
use App\Domain\Subject\SubjectRepository;

final class CreateSubjectHandler {
    public function __construct(private readonly SubjectRepository $repository) {}

    public function handle(CreateSubjectCommand $command): void {
        if ($this->repository->find(new SubjectId($command->id))) {
            throw new \RuntimeException("L'assignatura ja existeix.");
        }

        $subject = new Subject(new SubjectId($command->id), $command->name);

        if ($command->teacherId) {
            $subject->assignTeacher(new TeacherId($command->teacherId));
        }

        $this->repository->save($subject);
    }
}