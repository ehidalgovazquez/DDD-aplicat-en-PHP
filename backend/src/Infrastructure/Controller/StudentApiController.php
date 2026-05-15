<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\RequestAPI;
use App\Infrastructure\Http\ResponseJson;
use App\Domain\Student\SqlStudentRepository;
use App\Domain\Course\SqlCourseRepository;
use App\Application\Student\CreateStudent\CreateStudentHandler;
use App\Application\Student\CreateStudent\CreateStudentCommand;
use App\Application\Student\UpdateStudent\UpdateStudentHandler;
use App\Application\Student\UpdateStudent\UpdateStudentCommand;
use App\Application\Student\DeleteStudent\DeleteStudentHandler;
use App\Application\Student\DeleteStudent\DeleteStudentCommand;
use App\Application\Student\EnrollStudent\EnrollStudentHandler;
use App\Application\Student\EnrollStudent\EnrollStudentCommand;
use App\Application\Student\UnenrollStudent\UnenrollStudentHandler;
use App\Application\Student\UnenrollStudent\UnenrollStudentCommand;
use App\Infrastructure\Middleware\AuthMiddleware;
use App\Domain\Student\StudentId;
use Doctrine\ORM\EntityManagerInterface;

final class StudentApiController {
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
        $repository = new SqlStudentRepository($entityManager);
        
        $students = $repository->all();
        $data = array_map(fn($student) => [
            'id' => $student->id()->value(),
            'name' => $student->name(),
            'email' => $student->email(),
            'course_id' => $student->courseId()?->value()
        ], $students);

        (new ResponseJson(200, "Llista d'estudiants", $data))->send();
    }

    public function show(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);

        $body = $request->getBody();
        $studentId = $id ?? $body['id'] ?? '';
        $student = $repository->find(new StudentId($studentId));

        if (!$student) {
            (new ResponseJson(404, "Estudiant no trobat"))->send();
        }

        (new ResponseJson(200, "Estudiant trobat", [
            'id' => $student->id()->value(),
            'name' => $student->name(),
            'email' => $student->email(),
            'course_id' => $student->courseId()?->value()
        ]))->send();
    }
    
    public function store(RequestAPI $request): void {
        AuthMiddleware::check();

        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);
        $repositoryCourse = new SqlCourseRepository($entityManager);
        $handler = new CreateStudentHandler($repository, $repositoryCourse);
        $body = $request->getBody();

        try {
            $command = new CreateStudentCommand(
                $body['id'] ?? '',
                $body['name'] ?? '',
                $body['email'] ?? '',
                $body['course_id'] ?? null
            );

            $handler->handle($command);

            (new ResponseJson(201, "Estudiant creat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function update(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $studentRepo = new SqlStudentRepository($entityManager);
        $courseRepo = new SqlCourseRepository($entityManager);
        
        try {
            $handler = new UpdateStudentHandler($studentRepo, $courseRepo);
            $body = $request->getBody();
            $studentId = $id ?? $body['id'] ?? '';
            
            $handler->handle(new UpdateStudentCommand(
                $studentId,
                $body['name'] ?? '',
                $body['email'] ?? '',
                $body['course_id'] ?? null
            ));

            (new ResponseJson(200, "Estudiant actualitzat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function delete(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);
        $handler = new DeleteStudentHandler($repository);
        $body = $request->getBody();
        $studentId = $id ?? $body['id'] ?? '';
        try {
            $handler->handle(new DeleteStudentCommand($studentId));
            (new ResponseJson(200, "Estudiant eliminat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function enroll(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $studentRepo = new SqlStudentRepository($entityManager);
        $courseRepo = new SqlCourseRepository($entityManager);
        $handler = new EnrollStudentHandler($studentRepo, $courseRepo);
        $body = $request->getBody();
        $studentId = $id ?? $body['student_id'] ?? '';

        try {
            $handler->handle(new EnrollStudentCommand(
                $studentId,
                $body['course_id'] ?? ''
            ));
            (new ResponseJson(200, "Estudiant inscrit al curs correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function unenroll(RequestAPI $request, ?string $id = null): void {
        $entityManager = $this->getEntityManager();
        $studentRepo = new SqlStudentRepository($entityManager);
        $courseRepo = new SqlCourseRepository($entityManager);
        $handler = new UnenrollStudentHandler($studentRepo, $courseRepo);
        $body = $request->getBody();
        $studentId = $id ?? $body['student_id'] ?? '';

        try {
            $handler->handle(new UnenrollStudentCommand(
                $studentId,
                $body['course_id'] ?? ''
            ));
            (new ResponseJson(200, "Estudiant desinscrit del curs correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }
}