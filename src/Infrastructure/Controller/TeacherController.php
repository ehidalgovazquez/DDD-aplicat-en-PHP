<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Persistence\Database;
use App\Domain\Teacher\SqlTeacherRepository;
use App\Application\Teacher\CreateTeacher\CreateTeacherHandler;
use App\Application\Teacher\CreateTeacher\CreateTeacherCommand;
use App\Application\Teacher\UpdateTeacher\UpdateTeacherHandler;
use App\Application\Teacher\UpdateTeacher\UpdateTeacherCommand;
use App\Application\Teacher\DeleteTeacher\DeleteTeacherHandler;
use App\Application\Teacher\DeleteTeacher\DeleteTeacherCommand;
use App\Domain\Teacher\TeacherId;

final class TeacherController {
    
    public function index(): void {
        $pdo = Database::getConnection();
        $repository = new SqlTeacherRepository($pdo);
        
        $teachers = $repository->all();
        include __DIR__ . '/../../templates/teacher/index.php';
    }

    public function create(): void {
        include __DIR__ . '/../../templates/teacher/create.php';
    }

    public function store(Request $request): void {
        $pdo = Database::getConnection();
        $repository = new SqlTeacherRepository($pdo);
        $handler = new CreateTeacherHandler($repository);

        try {
            $command = new CreateTeacherCommand(
                $request->get('id'),
                $request->get('name'),
                $request->get('email')
            );

            $handler->handle($command);

            $_SESSION['success'] = "Professor creat correctament.";
            header('Location: /teacher');
            exit;

        } catch (\RuntimeException $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /teacher/create');
            exit;
        }
    }

    public function edit(Request $request): void {
        $pdo = Database::getConnection();
        $teacherRepo = new SqlTeacherRepository($pdo);

        $teacherId = new TeacherId($request->get('id'));
        $teacher = $teacherRepo->find($teacherId);

        if (!$teacher) {
            $_SESSION['error'] = "Professor no trobat.";
            header('Location: /teacher');
            exit;
        }

        include __DIR__ . '/../../templates/teacher/edit.php';
    }

    public function update(Request $request): void {
        $pdo = Database::getConnection();
        $teacherRepo = new SqlTeacherRepository($pdo);
        $handler = new UpdateTeacherHandler($teacherRepo);

        try {
            $command = new UpdateTeacherCommand(
                $request->get('id'),
                $request->get('name'),
                $request->get('email')
            );

            $handler->handle($command);
            $_SESSION['success'] = "Professor actualitzat correctament!";
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /teacher');
        exit;
    }

    public function delete(Request $request): void {
        $pdo = Database::getConnection();
        $teacherRepo = new SqlTeacherRepository($pdo);
        $handler = new DeleteTeacherHandler($teacherRepo);

        try {
            $command = new DeleteTeacherCommand($request->get('id'));
            $handler->handle($command);
            $_SESSION['success'] = "Professor eliminat correctament!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "No es pot eliminar: " . $e->getMessage();
        }

        header('Location: /teacher');
        exit;
    }
}