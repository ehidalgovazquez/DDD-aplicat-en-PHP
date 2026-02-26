<?php

namespace Tests\Application\Student;

use PHPUnit\Framework\TestCase;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use App\Application\Student\DeleteStudent\DeleteStudentHandler;
use App\Application\Student\DeleteStudent\DeleteStudentCommand;

final class DeleteStudentTest extends TestCase
{
    public function test_it_should_delete_a_student_successfully(): void
    {
        $studentId = 'student-1';
        $student = new Student(new StudentId($studentId), 'Juan', 'juan@test.com');

        $studentRepo = $this->createMock(StudentRepository::class);
        
        $studentRepo->method('find')->willReturn($student);
        $studentRepo->expects($this->once())->method('delete');

        $handler = new DeleteStudentHandler($studentRepo);
        $handler->handle(new DeleteStudentCommand($studentId));
        
        $this->assertTrue(true);
    }

    public function test_it_throws_exception_if_student_to_delete_does_not_exist(): void
    {
        $studentRepo = $this->createMock(StudentRepository::class);
        $studentRepo->method('find')->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("No es pot eliminar: l'estudiant no existeix.");

        $handler = new DeleteStudentHandler($studentRepo);
        $handler->handle(new DeleteStudentCommand('id-inexistente'));
    }
}