<?php

namespace Tests\Application\Student;

use PHPUnit\Framework\TestCase;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use App\Application\Student\CreateStudentHandler;
use App\Application\Student\CreateStudent\CreateStudentCommand;

final class CreateStudentTest extends TestCase
{
    public function test_it_should_create_a_student(): void {
        
        $studentId = 'student-1';
        $name = 'Juan Perez';
        $email = 'juan@example.com';

        $student = new Student(new StudentId($studentId), $name, $email);

        $studentRepository = $this->createMock(StudentRepository::class);
        $studentRepository->method('find')->willReturn($student); 
        $studentRepository->expects($this->once())->method('save');


        $handler = new CreateStudentHandler($studentRepository);
        
        $command = new CreateStudentCommand($studentId, $name, $email);

        $handler->handle($command);
        
        $this->assertTrue(true);
    }
}