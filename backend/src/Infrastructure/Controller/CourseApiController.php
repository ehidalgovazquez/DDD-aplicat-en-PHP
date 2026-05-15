<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\RequestAPI;
use App\Infrastructure\Http\ResponseJson;
use App\Domain\Course\SqlCourseRepository;
use App\Application\Course\CreateCourse\CreateCourseHandler;
use App\Application\Course\CreateCourse\CreateCourseCommand;
use App\Application\Course\UpdateCourse\UpdateCourseHandler;
use App\Application\Course\UpdateCourse\UpdateCourseCommand;
use App\Application\Course\DeleteCourse\DeleteCourseHandler;
use App\Application\Course\DeleteCourse\DeleteCourseCommand;
use App\Infrastructure\Middleware\AuthMiddleware;
use App\Domain\Course\CourseId;
use Doctrine\ORM\EntityManagerInterface;

final class CourseApiController {
    private RequestAPI $request;
    private EntityManagerInterface $entityManager;

    public function __construct(RequestAPI $request, EntityManagerInterface $entityManager) {
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    private function getEntityManager(): EntityManagerInterface {
        return $this->entityManager;
    }

    public function index(): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlCourseRepository($entityManager);

        $courses = $repository->all();
        $data = array_map(fn($course) => [
            'id' => $course->id()->value(),
            'name' => $course->name()
        ], $courses);

        (new ResponseJson(200, "Llista de cursos", $data))->send();
    }

    public function show(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlCourseRepository($entityManager);

        $body = $request->getBody();
        $courseId = $id ?? $body['id'] ?? '';
        $course = $repository->find(new CourseId($courseId));

        if (!$course) {
            (new ResponseJson(404, "Curs no trobat"))->send();
        }

        (new ResponseJson(200, "Curs trobat", [
            'id' => $course->id()->value(),
            'name' => $course->name()
        ]))->send();
    }

    public function store(RequestAPI $request): void {
        AuthMiddleware::check();

        $entityManager = $this->getEntityManager();
        $repository = new SqlCourseRepository($entityManager);
        $handler = new CreateCourseHandler($repository);
        $body = $request->getBody();

        try {
            $command = new CreateCourseCommand(
                $body['id'] ?? '',
                $body['name'] ?? ''
            );

            $handler->handle($command);

            (new ResponseJson(201, "Curs creat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function update(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlCourseRepository($entityManager);
        $handler = new UpdateCourseHandler($repository);
        $body = $request->getBody();
        $courseId = $id ?? $body['id'] ?? '';

        try {
            $command = new UpdateCourseCommand(
                $courseId,
                $body['name'] ?? ''
            );

            $handler->handle($command);

            (new ResponseJson(200, "Curs actualitzat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function delete(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlCourseRepository($entityManager);
        $handler = new DeleteCourseHandler($repository);
        $body = $request->getBody();
        $courseId = $id ?? $body['id'] ?? '';

        try {
            $command = new DeleteCourseCommand($courseId);
            $handler->handle($command);
            (new ResponseJson(200, "Curs eliminat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }
}