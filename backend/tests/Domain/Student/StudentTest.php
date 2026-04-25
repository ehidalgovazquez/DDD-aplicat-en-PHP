<?php

namespace Tests\Domain\Student;

use PHPUnit\Framework\TestCase;
use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Course\CourseId;

class StudentTest extends TestCase
{
    public function test_student_can_be_enrolled_into_a_course(): void
    {
        $student = new Student(StudentId::generate(), 'Juan Perez', 'juan@example.com');
        $courseId = new CourseId('course-123');

        $student->enrollInto($courseId);

        $this->assertEquals($courseId->value(), $student->courseId()->value());
    }

    public function test_student_can_be_unenrolled(): void
    {
        $student = new Student(StudentId::generate(), 'Juan Perez', 'juan@example.com');
        $student->enrollInto(new CourseId('course-1'));

        $student->unenroll();

        $this->assertNull($student->courseId());
    }

    public function test_student_can_enroll_again_after_unenroll(): void
    {
        $student = new Student(StudentId::generate(), 'Juan Perez', 'juan@example.com');
        
        $student->enrollInto(new CourseId('course-1'));
        $student->unenroll();
        
        $student->enrollInto(new CourseId('course-2'));
        
        $this->assertEquals('course-2', $student->courseId()->value());
    }
}