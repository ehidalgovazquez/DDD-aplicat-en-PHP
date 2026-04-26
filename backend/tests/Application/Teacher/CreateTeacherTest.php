<?php

namespace Tests\Application\Teacher;

use PHPUnit\Framework\TestCase;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;
use App\Application\Teacher\CreateTeacher\CreateTeacherHandler;
use App\Application\Teacher\CreateTeacher\CreateTeacherCommand;

final class CreateTeacherTest extends TestCase
{
    public function test_it_should_create_a_teacher_successfully(): void
    {
        $teacherId = 'teacher-1';
        $name = 'Profe DDD';
        $email = 'profe@example.com';

        $teacherRepository = $this->createMock(TeacherRepository::class);
        $teacherRepository->method('find')->willReturn(null); 
        $teacherRepository->expects($this->once())->method('save');

        $handler = new CreateTeacherHandler($teacherRepository);
        $command = new CreateTeacherCommand($teacherId, $name, $email);

        $handler->handle($command);
        
        $this->assertTrue(true);
    }

    public function test_it_should_throw_exception_if_teacher_already_exists(): void
    {
        $teacherId = 'teacher-1';
        $name = 'Profe DDD';
        $email = 'profe@example.com';
        
        $existingTeacher = new Teacher(new TeacherId($teacherId), $name, $email);

        $teacherRepository = $this->createMock(TeacherRepository::class);
        $teacherRepository->method('find')->willReturn($existingTeacher);
        $teacherRepository->expects($this->never())->method('save');

        $handler = new CreateTeacherHandler($teacherRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("El professor amb ID '$teacherId' ja existeix.");

        $command = new CreateTeacherCommand($teacherId, 'Otro Nombre', 'otro@email.com');
        $handler->handle($command);
    }
}