<?php

namespace App\Application\Subject\DeleteSubject;

use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;

final class DeleteSubjectHandler
{
    public function __construct(
        private readonly SubjectRepository $repository
    ) {}

    public function handle(DeleteSubjectCommand $command): void
    {
        $subjectId = new SubjectId($command->id);
        $subject = $this->repository->find($subjectId);

        if (!$subject) {
            throw new \RuntimeException("No es pot eliminar: l'assignatura no existeix.");
        }

        $this->repository->delete($subjectId);
    }
}