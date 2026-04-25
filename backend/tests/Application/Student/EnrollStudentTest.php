<?php

namespace Tests\Application\Student;

use PHPUnit\Framework\TestCase;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseRepository;
use App\Application\Student\EnrollStudent\EnrollStudentHandler;
use App\Application\Student\EnrollStudent\EnrollStudentCommand;

final class EnrollStudentTest extends TestCase
{
    public function test_it_should_enroll_a_student_into_a_course(): void
    {
        $studentId = 'student-1';
        $studentName = 'Juan Perez';
        $studentEmail = 'juan@example.com';

        $student = new Student(new StudentId($studentId), $studentName, $studentEmail);
        
        $courseId = 'course-1';
        $courseName = 'PHP DDD';
        $course = new Course(new CourseId($courseId), $courseName);

        $studentRepository = $this->createMock(StudentRepository::class);
        $courseRepository = $this->createMock(CourseRepository::class);

        $studentRepository->method('find')->willReturn($student);
        $courseRepository->method('find')->willReturn($course);

        $studentRepository->expects($this->once())->method('update');

        $handler = new EnrollStudentHandler($studentRepository, $courseRepository);
        $command = new EnrollStudentCommand($studentId, $courseId);

        $handler->handle($command);

        $this->assertEquals($courseId, $student->courseId()->value());
    }

    public function test_it_throws_exception_if_course_not_found(): void
    {
        $studentId = 'student-1';
        $studentName = 'Juan Perez';
        $studentEmail = 'juan@example.com';

        $student = new Student(new StudentId($studentId), $studentName, $studentEmail);
        
        $studentRepository = $this->createMock(StudentRepository::class);
        $courseRepository = $this->createMock(CourseRepository::class);

        $studentRepository->method('find')->willReturn($student);
        $courseRepository->method('find')->willReturn(null);

        $handler = new EnrollStudentHandler($studentRepository, $courseRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Course not found');

        $handler->handle(new EnrollStudentCommand($studentId, 'missing-course'));
    }

    public function test_it_throws_exception_if_student_not_found(): void
    {
        $courseId = 'course-1';
        $courseName = 'PHP DDD';
        $course = new Course(new CourseId($courseId), $courseName);
        
        $studentRepository = $this->createMock(StudentRepository::class);
        $courseRepository = $this->createMock(CourseRepository::class);

        $studentRepository->method('find')->willReturn(null);
        $courseRepository->method('find')->willReturn($course);

        $handler = new EnrollStudentHandler($studentRepository, $courseRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Student not found');

        $handler->handle(new EnrollStudentCommand('missing-student', $courseId));
    }
}