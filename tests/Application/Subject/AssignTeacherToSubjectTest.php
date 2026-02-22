<?php

namespace Tests\Application\Subject;

use PHPUnit\Framework\TestCase;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Subject\SubjectRepository;
use App\Domain\Teacher\TeacherRepository;
use App\Application\Subject\AssignTeacherHandler;
use App\Application\Subject\AssignTeacher\AssignTeacherCommand;

final class AssignTeacherToSubjectTest extends TestCase
{
    public function test_it_should_assign_a_teacher_to_a_subject(): void
    {
        $subjectId = 'subject-1';
        $subject = new Subject(new SubjectId($subjectId), 'Matemáticas');

        $teacherId = 'teacher-1';
        $teacher = new Teacher(new TeacherId($teacherId), 'Profe Juan', 'juan@escuela.com');

        $subjectRepository = $this->createMock(SubjectRepository::class);
        $teacherRepository = $this->createMock(TeacherRepository::class);

        $subjectRepository->method('find')->willReturn($subject);
        $teacherRepository->method('find')->willReturn($teacher);

        $subjectRepository->expects($this->once())->method('save');

        $handler = new AssignTeacherHandler($subjectRepository, $teacherRepository);
        $command = new AssignTeacherCommand($subjectId, $teacherId);

        $handler->handle($command);

        $this->assertEquals($teacherId, $subject->teacherId()->value());
    }

    public function test_it_throws_exception_if_teacher_not_found(): void
    {
        $subjectId = 'subject-1';
        $subject = new Subject(new SubjectId($subjectId), 'Matemáticas');

        $subjectRepository = $this->createMock(SubjectRepository::class);
        $teacherRepository = $this->createMock(TeacherRepository::class);

        $subjectRepository->method('find')->willReturn($subject);
        $teacherRepository->method('find')->willReturn(null);

        $handler = new AssignTeacherHandler($subjectRepository, $teacherRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Teacher not found');

        $handler->handle(new AssignTeacherCommand($subjectId, 'non-existent-teacher'));
    }
}