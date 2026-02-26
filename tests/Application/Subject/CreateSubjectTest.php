<?php

namespace Tests\Application\Subject;

use PHPUnit\Framework\TestCase;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;
use App\Application\Subject\CreateSubject\CreateSubjectHandler;
use App\Application\Subject\CreateSubject\CreateSubjectCommand;

final class CreateSubjectTest extends TestCase
{
    private $repository;
    private $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(SubjectRepository::class);
        $this->handler = new CreateSubjectHandler($this->repository);
    }

    public function test_it_should_create_a_subject_successfully(): void
    {
        $command = new CreateSubjectCommand('subj-1', 'Matemàtiques', 'teacher-1');

        // Escenario positivo: el repositorio no devuelve nada
        $this->repository->method('find')->willReturn(null);
        $this->repository->expects($this->once())->method('save');

        $this->handler->handle($command);
    }

    public function test_it_should_throw_exception_when_subject_already_exists(): void
    {
        $id = 'subj-1';
        $command = new CreateSubjectCommand($id, 'Matemàtiques');
        
        $existingSubject = new Subject(new SubjectId($id), 'Nombre Existente');
        
        $this->repository->method('find')->willReturn($existingSubject);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("L'assignatura ja existeix.");

        $this->handler->handle($command);
    }
}