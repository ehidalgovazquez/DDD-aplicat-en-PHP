<?php

namespace Tests\Application\Subject;

use PHPUnit\Framework\TestCase;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;
use App\Domain\Subject\SubjectRepository;
use App\Application\Subject\UnassignTeacher\UnassignTeacherHandler;
use App\Application\Subject\UnassignTeacher\UnassignTeacherCommand;

final class UnassignTeacherTest extends TestCase
{
    public function test_it_should_unassign_a_teacher_from_a_subject(): void
    {
        $subjectId = 'subject-1';
        $subjectName = 'Matemáticas';
        $subject = new Subject(new SubjectId($subjectId), $subjectName);
        
        $teacherId = 'teacher-1';
        $subject->assignTeacher(new TeacherId($teacherId));

        $subjectRepository = $this->createMock(SubjectRepository::class);
        $subjectRepository->method('find')->willReturn($subject);
        $subjectRepository->expects($this->once())->method('save');

        $handler = new UnassignTeacherHandler($subjectRepository);
        $command = new UnassignTeacherCommand($subjectId);

        $handler->handle($command);

        $this->assertNull($subject->teacherId());
    }

    public function test_it_throws_exception_when_subject_not_found(): void
    {
        $subjectRepository = $this->createMock(SubjectRepository::class);
        $subjectRepository->method('find')->willReturn(null);

        $handler = new UnassignTeacherHandler($subjectRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Subject not found');

        $handler->handle(new UnassignTeacherCommand('non-existent'));
    }
}