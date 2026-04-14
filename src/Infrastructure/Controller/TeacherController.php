<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Http\Request;
use App\Domain\Teacher\SqlTeacherRepository;
use App\Application\Teacher\CreateTeacher\CreateTeacherHandler;
use App\Application\Teacher\CreateTeacher\CreateTeacherCommand;
use App\Application\Teacher\UpdateTeacher\UpdateTeacherHandler;
use App\Application\Teacher\UpdateTeacher\UpdateTeacherCommand;
use App\Application\Teacher\DeleteTeacher\DeleteTeacherHandler;
use App\Application\Teacher\DeleteTeacher\DeleteTeacherCommand;
use App\Domain\Teacher\TeacherId;
use Doctrine\ORM\EntityManagerInterface;

final class TeacherController {

    private function getEntityManager(): EntityManagerInterface {
        return require __DIR__ . '/../../../config/doctrine.php';
    }

    public function index(): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);
        
        $teachers = $repository->all();
        include __DIR__ . '/../../templates/teacher/index.php';
    }

    public function create(): void {
        include __DIR__ . '/../../templates/teacher/create.php';
    }

    public function store(Request $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);
        $handler = new CreateTeacherHandler($repository);

        try {
            $handler->handle(new CreateTeacherCommand(
                $request->get('id'),
                $request->get('name'),
                $request->get('email')
            ));
            header('Location: /teacher');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /teacher/create');
            exit;
        }
    }

    public function edit(Request $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);

        $teacher = $repository->find(new TeacherId($request->get('id')));

        if (!$teacher) {
            header('Location: /teacher');
            exit;
        }

        include __DIR__ . '/../../templates/teacher/edit.php';
    }

    public function update(Request $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);
        $handler = new UpdateTeacherHandler($repository);

        try {
            $handler->handle(new UpdateTeacherCommand(
                $request->get('id'),
                $request->get('name'),
                $request->get('email')
            ));
            header('Location: /teacher');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: /teacher/edit?id=' . $request->get('id'));
            exit;
        }
    }

    public function delete(Request $request): void {
        $entityManager = $this->getEntityManager();
        $repository = new SqlTeacherRepository($entityManager);
        $handler = new DeleteTeacherHandler($repository);

        try {
            $handler->handle(new DeleteTeacherCommand($request->get('id')));
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /teacher');
        exit;
    }
}