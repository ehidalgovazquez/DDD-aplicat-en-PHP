<?php

namespace Tests\Application\Subject;

use PHPUnit\Framework\TestCase;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Subject\SubjectRepository;
use App\Domain\Teacher\TeacherRepository;
use App\Application\Subject\AssignTeacher\AssignTeacherHandler;
use App\Application\Subject\AssignTeacher\AssignTeacherCommand;

final class AssignTeacherToSubjectTest extends TestCase
{
    public function test_it_should_assign_a_teacher_to_a_subject(): void
    {
        $subjectRepository = $this->createMock(SubjectRepository::class);
        $teacherRepository = $this->createMock(TeacherRepository::class);
        $handler = new AssignTeacherHandler($subjectRepository, $teacherRepository);
        
        $subjectId = 'subj-123';
        $teacherId = 'teach-456';
        $subject = new Subject(new SubjectId($subjectId), 'Programació');
        
        $subjectRepository->method('find')->willReturn($subject);
        
        $teacher = new Teacher(new TeacherId($teacherId), 'Pepe', 'pepe@test.com');
        $teacherRepository->method('find')->willReturn($teacher);

        $subjectRepository->expects($this->once())->method('save');

        $command = new AssignTeacherCommand($subjectId, $teacherId);
        $handler->handle($command);

        $this->assertEquals($teacherId, $subject->teacherId()->value());
    }

    public function test_it_should_throw_exception_if_teacher_does_not_exist(): void
    {
        $subjectRepository = $this->createMock(SubjectRepository::class);
        $teacherRepository = $this->createMock(TeacherRepository::class);
        $handler = new AssignTeacherHandler($subjectRepository, $teacherRepository);

        $subject = new Subject(new SubjectId('subj-1'), 'Mates');
        $subjectRepository->method('find')->willReturn($subject);
        $teacherRepository->method('find')->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Teacher not found');

        $command = new AssignTeacherCommand('subj-1', 'invalid-teacher');
        $handler->handle($command);
    }

    public function test_it_should_throw_exception_when_subject_not_found(): void
    {
        $subjectRepository = $this->createMock(SubjectRepository::class);
        $teacherRepository = $this->createMock(TeacherRepository::class);
        $handler = new AssignTeacherHandler($subjectRepository, $teacherRepository);

        $subjectRepository->method('find')->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Subject not found');

        $command = new AssignTeacherCommand('non-existent', 'teacher-1');
        $handler->handle($command);
    }
}