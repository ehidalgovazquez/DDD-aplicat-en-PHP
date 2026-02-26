<?php

namespace App\Application\Subject\AssignTeacher;

use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;
use App\Domain\Subject\SubjectRepository;
use App\Domain\Teacher\TeacherRepository;
use App\Application\Subject\AssignTeacher\AssignTeacherCommand;

class AssignTeacherHandler {
    public function __construct(
        public readonly SubjectRepository $subjectRepository,
        public readonly TeacherRepository $teacherRepository
    ) {}

    public function handle(AssignTeacherCommand $command): void {
        $subject = $this->subjectRepository->find(new SubjectId($command->subjectId));
        if (!$subject) {
            throw new \RuntimeException('Subject not found');
        }

        $teacher = $this->teacherRepository->find(new TeacherId($command->teacherId));
        if (!$teacher) {
            throw new \RuntimeException('Teacher not found');
        }

        $subject->unassignTeacher();
        $subject->assignTeacher(new TeacherId($command->teacherId));

        $this->subjectRepository->save($subject);
    }
}