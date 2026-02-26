<?php

namespace Tests\Application\Subject;

use PHPUnit\Framework\TestCase;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;
use App\Application\Subject\DeleteSubject\DeleteSubjectHandler;
use App\Application\Subject\DeleteSubject\DeleteSubjectCommand;

final class DeleteSubjectTest extends TestCase
{
    private $repository;
    private $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(SubjectRepository::class);
        $this->handler = new DeleteSubjectHandler($this->repository);
    }

    public function test_it_should_delete_existing_subject(): void
    {
        $idStr = 'subj-1';
        $subject = new Subject(new SubjectId($idStr), 'Mates');

        $this->repository->method('find')->willReturn($subject);
        
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($this->callback(fn($id) => $id->value() === $idStr));

        $this->handler->handle(new DeleteSubjectCommand($idStr));
    }

    public function test_it_should_throw_exception_when_subject_does_not_exist(): void
    {
        $this->repository->method('find')->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("No es pot eliminar: l'assignatura no existeix.");

        $this->handler->handle(new DeleteSubjectCommand('non-existent-id'));
    }
}