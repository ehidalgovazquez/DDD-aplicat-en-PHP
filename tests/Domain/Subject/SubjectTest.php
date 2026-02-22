<?php

namespace Tests\Domain\Subject;

use PHPUnit\Framework\TestCase;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;

class SubjectTest extends TestCase
{
    public function test_subject_can_be_assigned_a_teacher(): void
    {
        $subject = new Subject(new SubjectId('sub-1'), 'Matemáticas');
        $teacherId = new TeacherId('teach-123');

        $subject->assignTeacher($teacherId);

        $this->assertEquals($teacherId->value(), $subject->teacherId()->value());
    }

    public function test_assigning_teacher_to_subject_with_teacher_already_assigned_throws_exception(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Subject already has a teacher assigned.");

        $subject = new Subject(new SubjectId('sub-1'), 'Matemáticas');
        
        $subject->assignTeacher(new TeacherId('teach-1'));
        $subject->assignTeacher(new TeacherId('teach-2'));
    }

    public function test_teacher_can_be_unassigned_from_subject(): void
    {
        $subject = new Subject(new SubjectId('sub-1'), 'Matemáticas');
        $subject->assignTeacher(new TeacherId('teach-1'));

        $subject->unassignTeacher();

        $this->assertNull($subject->teacherId());
    }
}