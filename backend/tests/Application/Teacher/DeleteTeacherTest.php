<?php

namespace Tests\Application\Teacher;

use PHPUnit\Framework\TestCase;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;
use App\Application\Teacher\DeleteTeacher\DeleteTeacherHandler;
use App\Application\Teacher\DeleteTeacher\DeleteTeacherCommand;

final class DeleteTeacherTest extends TestCase
{
    public function test_it_should_delete_a_teacher_successfully(): void
    {
        $teacherId = 'teacher-1';
        $teacher = new Teacher(new TeacherId($teacherId), 'Profe a Borrar', 'borrar@test.com');

        $repo = $this->createMock(TeacherRepository::class);
        $repo->method('find')->willReturn($teacher);
        $repo->expects($this->once())->method('delete');

        $handler = new DeleteTeacherHandler($repo);
        $handler->handle(new DeleteTeacherCommand($teacherId));
        
        $this->assertTrue(true);
    }

    public function test_it_should_throw_exception_if_teacher_to_delete_not_found(): void
    {
        $repo = $this->createMock(TeacherRepository::class);
        $repo->method('find')->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("No es pot eliminar: el professor no existeix.");

        $handler = new DeleteTeacherHandler($repo);
        $handler->handle(new DeleteTeacherCommand('id-inexistente'));
    }
}