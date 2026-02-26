<?php

namespace Tests\Application\Student;

use PHPUnit\Framework\TestCase;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseRepository;
use App\Application\Student\UpdateStudent\UpdateStudentHandler;
use App\Application\Student\UpdateStudent\UpdateStudentCommand;


final class UpdateStudentTest extends TestCase {
    public function test_it_should_update_a_student_successfully(): void {
        $studentId = 'student-1';
        $student = new Student(new StudentId($studentId), 'Nombre Viejo', 'viejo@test.com');
        
        $studentRepository = $this->createMock(StudentRepository::class);
        $studentRepository->method('find')->willReturn($student);
        $studentRepository->expects($this->once())->method('update');

        $courseRepository = $this->createMock(CourseRepository::class);

        $handler = new UpdateStudentHandler($studentRepository, $courseRepository);
        
        $command = new UpdateStudentCommand($studentId, 'Nombre Nuevo', 'nuevo@test.com', null);
        $handler->handle($command);

        $this->assertEquals('Nombre Nuevo', $student->name());
        $this->assertEquals('nuevo@test.com', $student->email());
    }

    public function test_it_throws_exception_if_student_to_update_does_not_exist(): void{
        $studentRepository = $this->createMock(StudentRepository::class);
        $courseRepository = $this->createMock(CourseRepository::class);

        $studentRepository->method('find')->willReturn(null);

        $handler = new UpdateStudentHandler($studentRepository, $courseRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("L'estudiant no existeix.");

        $command = new UpdateStudentCommand('id-fantasma', 'Inexistente', 'null@test.com', null);
        $handler->handle($command);
    }
}