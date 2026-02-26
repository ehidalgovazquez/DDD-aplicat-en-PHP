<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Persistence\Database;
use App\Domain\Student\SqlStudentRepository;
use App\Domain\Course\SqlCourseRepository;
use App\Application\Student\CreateStudent\CreateStudentHandler;
use App\Application\Student\CreateStudent\CreateStudentCommand;
use App\Application\Student\EnrollStudent\EnrollStudentHandler;
use App\Application\Student\EnrollStudent\EnrollStudentCommand;
use App\Application\Student\UpdateStudent\UpdateStudentHandler;
use App\Application\Student\UpdateStudent\UpdateStudentCommand;
use App\Application\Student\DeleteStudent\DeleteStudentHandler;
use App\Application\Student\DeleteStudent\DeleteStudentCommand;
use App\Domain\Student\StudentId;
use PDO;

final class StudentController
{
    public function index(): void {
        $pdo = Database::getConnection();
        $studentRepository = new SqlStudentRepository($pdo);
        $courseRepository = new SqlCourseRepository($pdo);
        
        $students = $studentRepository->all();
        $courses = $courseRepository->all();
        include __DIR__ . '/../../templates/student/index.php';
    }
    
    public function create(): void {
        include __DIR__ . '/../../templates/student/create.php';
    }

    public function store(Request $request): void {
        $pdo = Database::getConnection();
        $repository = new SqlStudentRepository($pdo);
        $courseRepo = new SqlCourseRepository($pdo);
        $handler = new CreateStudentHandler($repository, $courseRepo);

        try {
            $command = new CreateStudentCommand(
                $request->get('id'),
                $request->get('name'),
                $request->get('email'),
                $request->get('course_id')
            );

            $handler->handle($command);
            header('Location: /student');
            exit;

        } catch (\RuntimeException $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /student/create');
            exit;
        }
    }

    public function enroll(Request $request): void {
        $pdo = Database::getConnection();
        $studentRepo = new SqlStudentRepository($pdo);
        $courseRepo = new SqlCourseRepository($pdo);
        
        $handler = new EnrollStudentHandler($studentRepo, $courseRepo);

        try {
            $command = new EnrollStudentCommand(
                $request->get('student_id'),
                $request->get('course_id')
            );

            $handler->handle($command);
            $_SESSION['success'] = "Matrícula actualitzada correctament!";
        } catch (\RuntimeException $e) {
            $_SESSION['error'] = "Error en matricular: " . $e->getMessage();
        }

        header('Location: /student');
        exit;
    }

    public function edit(Request $request): void {
        $pdo = Database::getConnection();
        $studentRepo = new SqlStudentRepository($pdo);
        $courseRepo = new SqlCourseRepository($pdo);

        $studentId = new StudentId($request->get('id'));
        $student = $studentRepo->find($studentId);

        if (!$student) {
            $_SESSION['error'] = "Estudiant no trobat.";
            header('Location: /student');
            exit;
        }

        $courses = $courseRepo->all();

        include __DIR__ . '/../../templates/student/edit.php';
    }

    public function update(Request $request): void {
        $pdo = Database::getConnection();
        $studentRepo = new SqlStudentRepository($pdo);
        $courseRepo = new SqlCourseRepository($pdo);
        
        $handler = new UpdateStudentHandler($studentRepo, $courseRepo);

        try {
            $command = new UpdateStudentCommand(
                $request->get('id'),
                $request->get('name'),
                $request->get('email'),
                $request->get('course_id')
            );

            $handler->handle($command);
            
            $_SESSION['success'] = "Estudiant actualitzat correctament!";
            header('Location: /student');
            exit;

        } catch (\RuntimeException $e) {
            $_SESSION['error'] = "Error al actualizar: " . $e->getMessage();
            header('Location: /student/edit?id=' . $request->get('id'));
            exit;
        }
    }

    public function delete(Request $request): void {
        $pdo = Database::getConnection();
        $repository = new SqlStudentRepository($pdo);
        $handler = new DeleteStudentHandler($repository);

        try {
            $command = new DeleteStudentCommand(
                $request->get('id')
            );

            $handler->handle($command);
            $_SESSION['success'] = "Estudiant eliminat correctament.";
        } catch (\RuntimeException $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /student');
        exit;
    }
}