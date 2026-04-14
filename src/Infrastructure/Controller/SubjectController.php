<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\Request;
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
use App\Domain\Subject\SubjectId;
use Doctrine\ORM\EntityManagerInterface;

final class SubjectController {

    private function getEntityManager(): EntityManagerInterface {
        return require __DIR__ . '/../../../config/doctrine.php';
    }

    public function index(): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlSubjectRepository($entityManager);
        
        $subjects = $repository->all();
        include __DIR__ . '/../../templates/subject/index.php';
    }

    public function create(): void {
        include __DIR__ . '/../../templates/subject/create.php';
    }

    public function store(Request $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlSubjectRepository($entityManager);
        $handler = new CreateSubjectHandler($repository);

        try {
            $handler->handle(new CreateSubjectCommand(
                $request->get('id'),
                $request->get('name')
            ));
            header('Location: /subject');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /subject/create');
            exit;
        }
    }

    public function edit(Request $request): void {
        $entityManager = $this->getEntityManager();
        $subjectRepo = new SqlSubjectRepository($entityManager);
        $teacherRepo = new SqlTeacherRepository($entityManager);

        $subject = $subjectRepo->find(new SubjectId($request->get('id')));
        $teachers = $teacherRepo->all();

        if (!$subject) {
            header('Location: /subject');
            exit;
        }

        include __DIR__ . '/../../templates/subject/edit.php';
    }

    public function update(Request $request): void {
        $entityManager = $this->getEntityManager();
        $subjectRepo = new SqlSubjectRepository($entityManager);
        $teacherRepo = new SqlTeacherRepository($entityManager);

        try {
            $handler = new UpdateSubjectHandler($subjectRepo);
            $handler->handle(new UpdateSubjectCommand($request->get('id'), $request->get('name')));

            $subject = $subjectRepo->find(new SubjectId($request->get('id')));
            $newTeacherId = $request->get('teacher_id');

            if (empty($newTeacherId) && $subject->teacherId() !== null) {
                $unassignHandler = new UnassignTeacherHandler($subjectRepo);
                $unassignHandler->handle(new UnassignTeacherCommand($subject->id()->value()));
            } elseif (!empty($newTeacherId)) {
                $assignHandler = new AssignTeacherHandler($subjectRepo, $teacherRepo);
                $assignHandler->handle(new AssignTeacherCommand($subject->id()->value(), $newTeacherId));
            }

            header('Location: /subject');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /subject/edit?id=' . $request->get('id'));
            exit;
        }
    }

    public function delete(Request $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlSubjectRepository($entityManager);
        $handler = new DeleteSubjectHandler($repository);

        try {
            $handler->handle(new DeleteSubjectCommand($request->get('id')));
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /subject');
        exit;
    }
}