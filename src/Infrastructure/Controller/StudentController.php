<?php

namespace App\Infrastructure\Controller;

use App\Application\Student\CreateStudent\CreateStudentCommand;
use App\Application\Student\CreateStudentHandler;
use App\Application\Student\EnrollStudent\EnrollStudentCommand;
use App\Application\Student\EnrollStudentHandler;
use App\Application\Student\UnenrollStudent\UnenrollStudentCommand;
use App\Application\Student\UnenrollStudentHandler;

class StudentController
{
    public function __construct(
        private CreateStudentHandler $createHandler,
        private EnrollStudentHandler $enrollHandler,
        private UnenrollStudentHandler $unenrollHandler
    ) {}

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $command = new CreateStudentCommand(
                $_POST['id'],
                $_POST['name'],
                $_POST['email']
            );

            $this->createHandler->handle($command);
            header('Location: /students?message=created');
        }
    }

    public function enroll(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $command = new EnrollStudentCommand(
                $_POST['studentId'],
                $_POST['courseId']
            );

            $this->enrollHandler->handle($command);
            header('Location: /students/manage?id=' . $_POST['studentId']);
        }
    }

    public function unenroll(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $command = new UnenrollStudentCommand($_POST['studentId']);
            
            $this->unenrollHandler->handle($command);
            header('Location: /students/manage?id=' . $_POST['studentId']);
        }
    }
}