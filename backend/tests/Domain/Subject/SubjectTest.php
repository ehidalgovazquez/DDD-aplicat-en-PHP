<?php

namespace Tests\Domain\Subject;

use PHPUnit\Framework\TestCase;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;

final class SubjectTest extends TestCase
{
    public function test_it_should_create_subject_instance(): void
    {
        $id = new SubjectId('subj-1');
        $subject = new Subject($id, 'Matemàtiques');

        $this->assertEquals($id, $subject->id());
        $this->assertEquals('Matemàtiques', $subject->name());
        $this->assertNull($subject->teacherId());
    }

    public function test_it_should_assign_a_teacher(): void
    {
        $subject = new Subject(new SubjectId('subj-1'), 'Matemàtiques');
        $teacherId = new TeacherId('teach-1');

        $subject->assignTeacher($teacherId);

        $this->assertEquals($teacherId, $subject->teacherId());
    }

    public function test_it_should_unassign_teacher(): void
    {
        $subject = new Subject(new SubjectId('subj-1'), 'Matemàtiques');
        $subject->assignTeacher(new TeacherId('teach-1'));
        
        $subject->unassignTeacher();
        
        $this->assertNull($subject->teacherId());
    }
}