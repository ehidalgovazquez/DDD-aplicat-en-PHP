<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Persistence\Database;
use App\Domain\Course\SqlCourseRepository;
use App\Application\Course\CreateCourse\CreateCourseHandler;
use App\Application\Course\CreateCourse\CreateCourseCommand;
use App\Application\Course\UpdateCourse\UpdateCourseHandler;
use App\Application\Course\UpdateCourse\UpdateCourseCommand;
use App\Application\Course\DeleteCourse\DeleteCourseHandler;
use App\Application\Course\DeleteCourse\DeleteCourseCommand;
use App\Domain\Course\CourseId;


final class CourseController {
    
    public function index(): void {
        $pdo = Database::getConnection();
        $repository = new SqlCourseRepository($pdo);
        
        $courses = $repository->all();
        include __DIR__ . '/../../templates/course/index.php';
    }

    public function create(): void {
        include __DIR__ . '/../../templates/course/create.php';
    }

    public function store(Request $request): void {
        $pdo = Database::getConnection();
        $repository = new SqlCourseRepository($pdo);
        $handler = new CreateCourseHandler($repository);

        try {
            $command = new CreateCourseCommand(
                $request->get('id'),
                $request->get('name')
            );

            $handler->handle($command);

            $_SESSION['success'] = "Curs creat correctament.";
            header('Location: /course');
            exit;

        } catch (\RuntimeException $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /course/create');
            exit;
        }
    }

    public function edit(Request $request): void {
        $pdo = Database::getConnection();
        $courseRepo = new SqlCourseRepository($pdo);

        $courseId = new CourseId($request->get('id'));
        $course = $courseRepo->find($courseId);

        if (!$course) {
            $_SESSION['error'] = "Curs no trobat.";
            header('Location: /course');
            exit;
        }

        include __DIR__ . '/../../templates/course/edit.php';
    }

    public function update(Request $request): void {
        $pdo = Database::getConnection();
        $courseRepo = new SqlCourseRepository($pdo);
        $handler = new UpdateCourseHandler($courseRepo);

        try {
            $command = new UpdateCourseCommand(
                $request->get('id'),
                $request->get('name')
            );

            $handler->handle($command);
            $_SESSION['success'] = "Curs actualitzat correctament!";
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /course');
        exit;
    }

    public function delete(Request $request): void {
        $pdo = Database::getConnection();
        $courseRepo = new SqlCourseRepository($pdo);
        $handler = new DeleteCourseHandler($courseRepo);

        try {
            $command = new DeleteCourseCommand($request->get('id'));
            $handler->handle($command);
            $_SESSION['success'] = "Curs eliminat correctament!";
        } catch (\Exception $e) {
            $_SESSION['error'] = "No es pot eliminar: " . $e->getMessage();
        }

        header('Location: /course');
        exit;
    }
}