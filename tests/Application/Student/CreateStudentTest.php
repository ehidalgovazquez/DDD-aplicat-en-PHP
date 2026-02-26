<?php

namespace Tests\Application\Student;

use PHPUnit\Framework\TestCase;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use App\Application\Student\CreateStudent\CreateStudentHandler;
use App\Application\Student\CreateStudent\CreateStudentCommand;

final class CreateStudentTest extends TestCase
{
    public function test_it_should_create_a_student(): void {
        $studentId = 'student-1';
        
        $studentRepository = $this->createMock(StudentRepository::class);
        $studentRepository->method('find')->willReturn(null); 
        $studentRepository->expects($this->once())->method('save');

        $courseRepository = $this->createMock(CourseRepository::class);

        $handler = new CreateStudentHandler($studentRepository, $courseRepository);
        $command = new CreateStudentCommand($studentId, 'Juan', 'juan@example.com', null);

        $handler->handle($command);
        $this->assertTrue(true);
    }

    public function test_it_should_throw_exception_if_student_id_already_exists(): void {
        $studentId = 'student-1';
        $existingStudent = new Student(new StudentId($studentId), 'Pepe', 'pepe@test.com');

        $studentRepository = $this->createMock(StudentRepository::class);
        $studentRepository->method('find')->willReturn($existingStudent);
        
        $courseRepository = $this->createMock(CourseRepository::class);

        $handler = new CreateStudentHandler($studentRepository, $courseRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("L'estudiant amb ID 'student-1' ja existeix.");

        $handler->handle(new CreateStudentCommand($studentId, 'Juan', 'juan@test.com', null));
    }
}