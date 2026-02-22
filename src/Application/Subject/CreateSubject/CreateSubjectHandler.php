<?php

namespace App\Application\Subject;

use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;
use App\Application\Subject\CreateSubject\CreateSubjectCommand;

class CreateSubjectHandler {
    public function __construct(
        public readonly SubjectRepository $subjectRepository
    ) {}

    public function handle(CreateSubjectCommand $command): void
    {
        $subject = new Subject(
            new SubjectId($command->id),
            $command->name
        );

        $this->subjectRepository->save($subject);
    }
}