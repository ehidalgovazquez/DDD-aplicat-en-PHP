<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\Request;
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
use App\Domain\Course\CourseId;
use Doctrine\ORM\EntityManagerInterface;

final class StudentController {
    private function getEntityManager(): EntityManagerInterface {
        return require __DIR__ . '/../../../config/doctrine.php';
    }

    public function index(): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);
        
        $students = $repository->all();
        include __DIR__ . '/../../templates/student/index.php';
    }

    public function show(Request $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);
        $courseRepo = new SqlCourseRepository($entityManager);
        $student = $repository->find(new StudentId($request->get('id', '')));
        
        if (!$student) {
            header('Location: /student');
            exit;
        }

        $course = null;
        if ($student->courseId() !== null) {
            $course = $courseRepo->find(new CourseId($student->courseId()));
        }

        include __DIR__ . '/../../templates/student/show.php';
    }

    public function create(): void {
        $entityManager = $this->getEntityManager();
        $courseRepo = new SqlCourseRepository($entityManager);
        $courses = $courseRepo->all();
        
        include __DIR__ . '/../../templates/student/create.php';
    }

    public function store(Request $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);
        $repositoryCourse = new SqlCourseRepository($entityManager);
        $handler = new CreateStudentHandler($repository, $repositoryCourse);

        try {
            $command = new CreateStudentCommand(
                $request->get('id', ''),
                $request->get('name', ''),
                $request->get('email', ''),
                $request->get('course_id', null)
            );
            $handler->handle($command);
            header('Location: /student');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /student/create');
            exit;
        }
    }

    public function edit(Request $request): void {
        $entityManager = $this->getEntityManager();
        $studentRepo = new SqlStudentRepository($entityManager);
        $courseRepo = new SqlCourseRepository($entityManager);
        $student = $studentRepo->find(new StudentId($request->get('id', '')));
        $courses = $courseRepo->all();

        if (!$student) {
            header('Location: /student');
            exit;
        }

        include __DIR__ . '/../../templates/student/edit.php';
    }

    public function update(Request $request): void {
        $entityManager = $this->getEntityManager();
        $studentRepo = new SqlStudentRepository($entityManager);
        $courseRepo = new SqlCourseRepository($entityManager);
        
        try {
            $handler = new UpdateStudentHandler($studentRepo, $courseRepo);
            
            $handler->handle(new UpdateStudentCommand(
                $request->get('id', ''),
                $request->get('name', ''),
                $request->get('email', ''),
                $request->get('course_id', null)
            ));

            $student = $studentRepo->find(new StudentId($request->get('id', '')));
            $newCourseId = $request->get('course_id', null);

            if (empty($newCourseId) && $student->courseId() !== null) {
                $unenrollHandler = new UnenrollStudentHandler($studentRepo);
                $unenrollHandler->handle(new UnenrollStudentCommand($student->id()->value()));
            } elseif (!empty($newCourseId)) {
                $enrollHandler = new EnrollStudentHandler($studentRepo, $courseRepo);
                $enrollHandler->handle(new EnrollStudentCommand($student->id()->value(), $newCourseId));
            }

            header('Location: /student');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /student/edit?id=' . $request->get('id', ''));
            exit;
        }
    }

    public function delete(Request $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlStudentRepository($entityManager);
        $handler = new DeleteStudentHandler($repository);
        try {
            $handler->handle(new DeleteStudentCommand($request->get('id', '')));
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /student');
        exit;
    }
}