<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Persistence\Database;
use App\Domain\Subject\SqlSubjectRepository;
use App\Domain\Teacher\SqlTeacherRepository;
use App\Application\Subject\CreateSubject\CreateSubjectHandler;
use App\Application\Subject\CreateSubject\CreateSubjectCommand;
use App\Application\Subject\UpdateSubject\UpdateSubjectHandler;
use App\Application\Subject\UpdateSubject\UpdateSubjectCommand;
use App\Application\Subject\DeleteSubject\DeleteSubjectHandler;
use App\Application\Subject\DeleteSubject\DeleteSubjectCommand;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;

final class SubjectController {
    
    public function index(): void {
        $pdo = Database::getConnection();
        $repository = new SqlSubjectRepository($pdo);
        $teacherRepo = new SqlTeacherRepository($pdo);
        
        $subjects = $repository->all();
        $teachers = $teacherRepo->all();
        
        include __DIR__ . '/../../templates/subject/index.php';
    }

    public function create(): void {
        $pdo = Database::getConnection();
        $teacherRepo = new SqlTeacherRepository($pdo);
        
        $teachers = $teacherRepo->all();
        
        include __DIR__ . '/../../templates/subject/create.php';
    }

    public function store(Request $request): void {
        $pdo = Database::getConnection();
        $repository = new SqlSubjectRepository($pdo);
        $handler = new CreateSubjectHandler($repository);

        try {
            $command = new CreateSubjectCommand(
                $request->get('id'),
                $request->get('name'),
                $request->get('teacher_id') ?: null
            );

            $handler->handle($command);

            $_SESSION['success'] = "Assignatura creada correctament.";
            header('Location: /subject');
            exit;

        } catch (\RuntimeException $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /subject/create');
            exit;
        }
    }

    public function edit(Request $request): void {
        $pdo = Database::getConnection();
        $subjectRepo = new SqlSubjectRepository($pdo);
        $teacherRepo = new SqlTeacherRepository($pdo);

        $subjectId = new SubjectId($request->get('id'));
        $subject = $subjectRepo->find($subjectId);
        $teachers = $teacherRepo->all();

        if (!$subject) {
            $_SESSION['error'] = "Assignatura no trobada.";
            header('Location: /subject');
            exit;
        }

        include __DIR__ . '/../../templates/subject/edit.php';
    }

    public function update(Request $request): void {
        $pdo = Database::getConnection();
        $subjectRepo = new SqlSubjectRepository($pdo);
        $handler = new UpdateSubjectHandler($subjectRepo);

        try {
            $command = new UpdateSubjectCommand(
                $request->get('id'),
                $request->get('name'),
                $request->get('teacher_id') ?: null
            );

            $handler->handle($command);
            $_SESSION['success'] = "Assignatura actualitzada correctament!";
        } catch (\RuntimeException $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /subject');
        exit;
    }

    public function delete(Request $request): void {
        $pdo = Database::getConnection();
        $subjectRepo = new SqlSubjectRepository($pdo);
        $handler = new DeleteSubjectHandler($subjectRepo);

        try {
            $command = new DeleteSubjectCommand($request->get('id'));
            $handler->handle($command);
            $_SESSION['success'] = "Assignatura eliminada correctament!";
        } catch (\RuntimeException $e) {
            $_SESSION['error'] = "No es pot eliminar: " . $e->getMessage();
        }

        header('Location: /subject');
        exit;
    }

    public function assignTeacher(Request $request): void {
        $pdo = Database::getConnection();
        $repository = new SqlSubjectRepository($pdo);
        $subject = $repository->find(new SubjectId($request->get('subject_id')));
        $teacherId = $request->get('teacher_id');
        
        if ($subject) {
            if ($teacherId) {
                $subject->assignTeacher(new TeacherId($teacherId));
                $_SESSION['success'] = "Professor assignat correctament!";
            } else {
                $subject->unassignTeacher();
                $_SESSION['success'] = "Professor desassignat correctament!";
            }
            $repository->update($subject);
        }
        
        header('Location: /subject');
        exit;
    }
}