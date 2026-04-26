<?php

namespace Tests\Application\Subject;

use PHPUnit\Framework\TestCase;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;
use App\Application\Subject\UpdateSubject\UpdateSubjectHandler;
use App\Application\Subject\UpdateSubject\UpdateSubjectCommand;

final class UpdateSubjectTest extends TestCase
{
    public function test_it_should_update_subject_details(): void
    {
        $repository = $this->createMock(SubjectRepository::class);
        
        $id = new SubjectId('subj-1');
        $subject = new Subject($id, 'Nom Antic');
        
        $repository->method('find')->willReturn($subject);
        $repository->expects($this->once())->method('update');

        $handler = new UpdateSubjectHandler($repository);
        $command = new UpdateSubjectCommand('subj-1', 'Nom Nou', 'teacher-uuid');

        $handler->handle($command);

        $this->assertEquals('Nom Nou', $subject->name());
        $this->assertEquals('teacher-uuid', $subject->teacherId()->value());
    }

    public function test_it_should_throw_exception_when_subject_does_not_exist(): void
    {
        $repository = $this->createMock(SubjectRepository::class);
        $repository->method('find')->willReturn(null);
        $repository->expects($this->never())->method('update');

        $handler = new UpdateSubjectHandler($repository);
        $command = new UpdateSubjectCommand('non-existent', 'Cualquier nombre');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("La asignatura no existe."); // Asegúrate que este mensaje coincide con tu Handler

        $handler->handle($command);
    }
}