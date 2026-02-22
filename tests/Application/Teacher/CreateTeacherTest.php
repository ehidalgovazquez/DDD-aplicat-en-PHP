<?php

namespace Tests\Application\Teacher;

use PHPUnit\Framework\TestCase;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;
use App\Application\Teacher\CreateTeacherHandler;
use App\Application\Teacher\CreateTeacher\CreateTeacherCommand;

final class CreateTeacherTest extends TestCase
{
    public function test_it_should_create_a_teacher(): void
    {
        $teacherId = 'teacher-1';
        $name = 'Profe DDD';
        $email = 'profe@example.com';

        $teacher = new Teacher(new TeacherId($teacherId), $name, $email);

        $teacherRepository = $this->createMock(TeacherRepository::class);
        $teacherRepository->method('find')->willReturn($teacher); 
        $teacherRepository->expects($this->once())->method('save');

        $handler = new CreateTeacherHandler($teacherRepository);
        $command = new CreateTeacherCommand($teacherId, $name, $email);

        $handler->handle($command);
        
        $this->assertTrue(true);
    }
}