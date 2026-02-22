<?php

namespace App\Infrastructure\Controller;

use App\Application\Subject\AssignTeacherHandler;
use App\Application\Subject\AssignTeacher\AssignTeacherCommand;
use App\Application\Subject\UnassignTeacher\UnassignTeacherHandler;
use App\Application\Subject\UnassignTeacher\UnassignTeacherCommand;

class SubjectController
{
    public function __construct(
        private AssignTeacherHandler $assignHandler,
        private UnassignTeacherHandler $unassignHandler
    ) {}

    public function assignTeacher(): void
    {
        $command = new AssignTeacherCommand(
            $_POST['subjectId'],
            $_POST['teacherId']
        );

        $this->assignHandler->handle($command);
        header('Location: /subjects');
    }

    public function unassignTeacher(): void
    {
        $command = new UnassignTeacherCommand($_POST['subjectId']);
        
        $this->unassignHandler->handle($command);
        header('Location: /subjects');
    }
}