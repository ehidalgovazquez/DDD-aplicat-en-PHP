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
use App\Domain\Student\StudentId;
use Doctrine\ORM\EntityManagerInterface;

final class StudentApiController {
    private function getEntityManager(): EntityManagerInterface {
        return require __DIR__ . '/../../../config/doctrine.php';
    }

    public function index(): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);
        
        $students = $repository->all();
        $data = array_map(fn($student) => [
            'id' => $student->id()->value(),
            'name' => $student->name(),
            'email' => $student->email(),
            'course_id' => $student->courseId()
        ], $students);

        (new ResponseJson(200, "Llista d'estudiants", $data))->send();
    }

    public function show(RequestAPI $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);

        $body = $request->getBody();
        $student = $repository->find(new StudentId($body['id'] ?? ''));

        if (!$student) {
            (new ResponseJson(404, "Estudiant no trobat"))->send();
        }

        (new ResponseJson(200, "Estudiant trobat", [
            'id' => $student->id()->value(),
            'name' => $student->name(),
            'email' => $student->email(),
            'course_id' => $student->courseId()
        ]))->send();
    }
    
    public function store(RequestAPI $request): void {
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

    public function update(RequestAPI $request): void {
        $entityManager = $this->getEntityManager();
        $studentRepo = new SqlStudentRepository($entityManager);
        $courseRepo = new SqlCourseRepository($entityManager);
        
        try {
            $handler = new UpdateStudentHandler($studentRepo, $courseRepo);
            $body = $request->getBody();
            
            $handler->handle(new UpdateStudentCommand(
                $body['id'] ?? '',
                $body['name'] ?? '',
                $body['email'] ?? '',
                $body['course_id'] ?? null
            ));

            (new ResponseJson(200, "Estudiant actualitzat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function delete(RequestAPI $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);
        $handler = new DeleteStudentHandler($repository);
        $body = $request->getBody();
        try {
            $handler->handle(new DeleteStudentCommand($body['id'] ?? ''));
            (new ResponseJson(200, "Estudiant eliminat correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function enroll(RequestAPI $request): void {
        $entityManager = $this->getEntityManager();
        $studentRepo = new SqlStudentRepository($entityManager);
        $courseRepo = new SqlCourseRepository($entityManager);
        $handler = new EnrollStudentHandler($studentRepo, $courseRepo);
        $body = $request->getBody();

        try {
            $handler->handle(new EnrollStudentCommand(
                $body['student_id'] ?? '',
                $body['course_id'] ?? ''
            ));
            (new ResponseJson(200, "Estudiant inscrit al curs correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }

    public function unenroll(RequestAPI $request): void {
        $entityManager = $this->getEntityManager();
        $studentRepo = new SqlStudentRepository($entityManager);
        $courseRepo = new SqlCourseRepository($entityManager);
        $handler = new UnenrollStudentHandler($studentRepo, $courseRepo);
        $body = $request->getBody();

        try {
            $handler->handle(new UnenrollStudentCommand(
                $body['student_id'] ?? '',
                $body['course_id'] ?? ''
            ));
            (new ResponseJson(200, "Estudiant desinscrit del curs correctament"))->send();
        } catch (\Exception $e) {
            (new ResponseJson(400, "Error: " . $e->getMessage()))->send();
        }
    }
}