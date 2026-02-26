<?php

namespace Tests\Application\Teacher;

use PHPUnit\Framework\TestCase;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;
use App\Application\Teacher\UpdateTeacher\UpdateTeacherHandler;
use App\Application\Teacher\UpdateTeacher\UpdateTeacherCommand;

final class UpdateTeacherTest extends TestCase
{
    public function test_it_should_update_a_teacher_successfully(): void
    {
        $teacherId = 'teacher-1';
        $teacher = new Teacher(new TeacherId($teacherId), 'Nombre Viejo', 'viejo@test.com');

        $repo = $this->createMock(TeacherRepository::class);
        
        $repo->method('find')->willReturn($teacher);
        $repo->expects($this->once())->method('update');

        $handler = new UpdateTeacherHandler($repo);
        $command = new UpdateTeacherCommand($teacherId, 'Nombre Nuevo', 'nuevo@test.com');

        $handler->handle($command);

        $this->assertEquals('Nombre Nuevo', $teacher->name());
        $this->assertEquals('nuevo@test.com', $teacher->email());
    }

    public function test_it_should_throw_exception_if_teacher_to_update_not_found(): void
    {
        $repo = $this->createMock(TeacherRepository::class);
        $repo->method('find')->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("El professor no existeix.");

        $handler = new UpdateTeacherHandler($repo);
        $handler->handle(new UpdateTeacherCommand('id-falso', 'Nombre', 'email@test.com'));
    }
}