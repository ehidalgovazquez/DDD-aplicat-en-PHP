<?php

namespace App\Application\Subject\UnassignTeacher;

use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;
use App\Application\Subject\UnassignTeacher\UnassignTeacherCommand;

final class UnassignTeacherHandler
{
    public function __construct(
        private SubjectRepository $subjectRepository
    ) {}

    public function handle(UnassignTeacherCommand $command): void
    {
        $subject = $this->subjectRepository->find(new SubjectId($command->subjectId));
        
        if (!$subject) {
            throw new \RuntimeException('Subject not found');
        }

        $subject->unassignTeacher();

        $this->subjectRepository->save($subject);
    }
}