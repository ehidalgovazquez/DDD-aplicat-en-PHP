<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\RequestAPI;
use App\Infrastructure\Http\ResponseJson;
use App\Domain\Teacher\SqlTeacherRepository;
use App\Application\Teacher\CreateTeacher\CreateTeacherHandler;
use App\Application\Teacher\CreateTeacher\CreateTeacherCommand;
use App\Application\Teacher\UpdateTeacher\UpdateTeacherHandler;
use App\Application\Teacher\UpdateTeacher\UpdateTeacherCommand;
use App\Application\Teacher\DeleteTeacher\DeleteTeacherHandler;
use App\Application\Teacher\DeleteTeacher\DeleteTeacherCommand;
use App\Infrastructure\Middleware\AuthMiddleware;
use App\Domain\Teacher\TeacherId;
use Doctrine\ORM\EntityManagerInterface;

final class TeacherApiController {
    private RequestAPI $request;
    private EntityManagerInterface $entityManager;

    public function __construct(RequestAPI $request, EntityManagerInterface $entityManager)
    {
        $this->request = $request;
        $this->entityManager = $entityManager;
    }

    private function getEntityManager(): EntityManagerInterface {
        return $this->entityManager;
    }

    public function index(): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);

        $teachers = $repository->all();
        $data = array_map(fn($teacher) => [
            'id' => $teacher->id()->value(),
            'name' => $teacher->name(),
            'email' => $teacher->email()
        ], $teachers);

        (new ResponseJson(200, "Llista de professors", $data))->send();
    }

    public function show(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);

        $body = $request->getBody();
        $teacherId = $id ?? $body['id'] ?? '';
        $teacher = $repository->find(new TeacherId($teacherId));

        if (!$teacher) {
            (new ResponseJson(404, "Professor no trobat"))->send();
        }

        (new ResponseJson(200, "Professor trobat", [
            'id' => $teacher->id()->value(),
            'name' => $teacher->name(),
            'email' => $teacher->email()
        ]))->send();
    }

    public function store(RequestAPI $request): void {
        AuthMiddleware::check();
        
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);
        $handler = new CreateTeacherHandler($repository);
        $body = $request->getBody();

        try {
            $handler->handle(new CreateTeacherCommand(
                $body['id'] ?? '',
                $body['name'] ?? '',
                $body['email'] ?? ''
            ));

            (new ResponseJson(201, "Professor creat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function update(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);
        $handler = new UpdateTeacherHandler($repository);
        $body = $request->getBody();
        $teacherId = $id ?? $body['id'] ?? '';

        try {
            $handler->handle(new UpdateTeacherCommand(
                $teacherId,
                $body['name'] ?? '',
                $body['email'] ?? ''
            ));

            (new ResponseJson(200, "Professor actualitzat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function delete(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);
        $handler = new DeleteTeacherHandler($repository);
        $body = $request->getBody();
        $teacherId = $id ?? $body['id'] ?? '';

        try {
            $handler->handle(new DeleteTeacherCommand($teacherId));
            (new ResponseJson(200, "Professor eliminat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }
}
