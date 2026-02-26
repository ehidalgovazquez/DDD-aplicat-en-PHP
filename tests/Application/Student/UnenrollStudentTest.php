<?php

namespace Tests\Application\Student;

use PHPUnit\Framework\TestCase;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Course\CourseId;
use App\Domain\Student\StudentRepository;
use App\Application\Student\UnenrollStudent\UnenrollStudentHandler;
use App\Application\Student\UnenrollStudent\UnenrollStudentCommand;

final class UnenrollStudentTest extends TestCase
{
    public function test_it_should_unenroll_a_student(): void
    {
        $studentId = 'student-1';
        $studentName = 'Juan Perez';
        $studentEmail = 'juan@example.com';

        $student = new Student(new StudentId($studentId), $studentName, $studentEmail);
        
        $courseId = 'course-100';
        $student->enrollInto(new CourseId($courseId));

        $studentRepository = $this->createMock(StudentRepository::class);
        
        $studentRepository->method('find')->willReturn($student);
        
        $studentRepository->expects($this->once())->method('save');

        $handler = new UnenrollStudentHandler($studentRepository);
        $command = new UnenrollStudentCommand($studentId);

        $handler->handle($command);

        $this->assertNull($student->courseId());
    }

    public function test_it_throws_exception_when_student_to_unenroll_not_found(): void
    {
        $studentRepository = $this->createMock(StudentRepository::class);
        $studentRepository->method('find')->willReturn(null);

        $handler = new UnenrollStudentHandler($studentRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Student not found');

        $handler->handle(new UnenrollStudentCommand('non-existent'));
    }
}