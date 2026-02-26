<?php

namespace App\Application\Subject\UpdateSubject;

use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;
use App\Domain\Subject\SubjectRepository;

final class UpdateSubjectHandler {
    public function __construct(private readonly SubjectRepository $repository) {}

    public function handle(UpdateSubjectCommand $command): void {
        $subject = $this->repository->find(new SubjectId($command->id));

        if (!$subject) {
            throw new \RuntimeException("La asignatura no existe.");
        }

        $subject->update($command->name);

        $subject->unassignTeacher();
        if ($command->teacherId) {
            $subject->assignTeacher(new TeacherId($command->teacherId));
        }
        
        $this->repository->update($subject);
    }
}