<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\RequestAPI;
use App\Infrastructure\Http\ResponseJson;
use App\Domain\Subject\SqlSubjectRepository;
use App\Domain\Teacher\SqlTeacherRepository;
use App\Application\Subject\CreateSubject\CreateSubjectHandler;
use App\Application\Subject\CreateSubject\CreateSubjectCommand;
use App\Application\Subject\UpdateSubject\UpdateSubjectHandler;
use App\Application\Subject\UpdateSubject\UpdateSubjectCommand;
use App\Application\Subject\DeleteSubject\DeleteSubjectHandler;
use App\Application\Subject\DeleteSubject\DeleteSubjectCommand;
use App\Application\Subject\AssignTeacher\AssignTeacherHandler;
use App\Application\Subject\AssignTeacher\AssignTeacherCommand;
use App\Application\Subject\UnassignTeacher\UnassignTeacherHandler;
use App\Application\Subject\UnassignTeacher\UnassignTeacherCommand;
use App\Infrastructure\Middleware\AuthMiddleware;
use App\Domain\Subject\SubjectId;
use Doctrine\ORM\EntityManagerInterface;

final class SubjectApiController {
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
        $subjectRepo = new SqlSubjectRepository($entityManager);

        $subjects = $subjectRepo->all();
        $data = array_map(fn($subject) => [
            'id' => $subject->id()->value(),
            'name' => $subject->name(),
            'teacher_id' => $subject->teacherId()?->value()
        ], $subjects);

        (new ResponseJson(200, "Llista de subjectes", $data))->send();
    }

    public function show(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlSubjectRepository($entityManager);

        $body = $request->getBody();
        $subjectId = $id ?? $body['id'] ?? '';
        $subject = $repository->find(new SubjectId($subjectId));

        if (!$subject) {
            (new ResponseJson(404, "Subject no trobat"))->send();
        }

        (new ResponseJson(200, "Subject trobat", [
            'id' => $subject->id()->value(),
            'name' => $subject->name(),
            'teacher_id' => $subject->teacherId()?->value()
        ]))->send();
    }
    
    public function store(RequestAPI $request): void {
        AuthMiddleware::check();
        
        $entityManager = $this->getEntityManager();
        $subjectRepo = new SqlSubjectRepository($entityManager);
        $teacherRepo = new SqlTeacherRepository($entityManager);
        $handler = new CreateSubjectHandler($subjectRepo, $teacherRepo);
        $body = $request->getBody();

        try {
            $command = new CreateSubjectCommand(
                $body['id'] ?? '',
                $body['name'] ?? '',
                $body['teacher_id'] ?? null
            );

            $handler->handle($command);

            (new ResponseJson(201, "Subject creat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function update(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $subjectRepo = new SqlSubjectRepository($entityManager);
        $teacherRepo = new SqlTeacherRepository($entityManager);
        
        try {
            $handler = new UpdateSubjectHandler($subjectRepo, $teacherRepo);
            $body = $request->getBody();
            $subjectId = $id ?? $body['id'] ?? '';
            
            $handler->handle(new UpdateSubjectCommand(
                $subjectId,
                $body['name'] ?? '',
                $body['teacher_id'] ?? null
            ));

            (new ResponseJson(200, "Subject actualitzat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function delete(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlSubjectRepository($entityManager);
        $handler = new DeleteSubjectHandler($repository);
        $body = $request->getBody();
        $subjectId = $id ?? $body['id'] ?? '';
        try {
            $handler->handle(new DeleteSubjectCommand($subjectId));
            (new ResponseJson(200, "Subject eliminat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function assignTeacher(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $subjectRepo = new SqlSubjectRepository($entityManager);
        $teacherRepo = new SqlTeacherRepository($entityManager);
        $handler = new AssignTeacherHandler($subjectRepo, $teacherRepo);
        $body = $request->getBody();
        $subjectId = $id ?? $body['subject_id'] ?? '';

        try {
            $handler->handle(new AssignTeacherCommand(
                $subjectId,
                $body['teacher_id'] ?? ''
            ));
            (new ResponseJson(200, "Professor assignat al subject correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function unassignTeacher(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $subjectRepo = new SqlSubjectRepository($entityManager);
        $teacherRepo = new SqlTeacherRepository($entityManager);
        $handler = new UnassignTeacherHandler($subjectRepo, $teacherRepo);
        $body = $request->getBody();
        $subjectId = $id ?? $body['subject_id'] ?? '';

        try {
            $handler->handle(new UnassignTeacherCommand(
                $subjectId,
                $body['teacher_id'] ?? ''
            ));
            (new ResponseJson(200, "Professor desassignat del subject correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }
}