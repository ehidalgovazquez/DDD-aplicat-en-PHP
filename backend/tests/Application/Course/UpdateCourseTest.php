<?php

namespace Tests\Application\Course;

use PHPUnit\Framework\TestCase;
use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use App\Application\Course\UpdateCourse\UpdateCourseHandler;
use App\Application\Course\UpdateCourse\UpdateCourseCommand;

final class UpdateCourseTest extends TestCase
{
    public function test_it_should_update_a_course_successfully(): void
    {
        $courseId = 'course-1';
        $course = new Course(new CourseId($courseId), 'Nom Antic');
        
        $repo = $this->createMock(CourseRepository::class);
        
        $repo->method('find')->willReturn($course);
        $repo->expects($this->once())->method('update');

        $handler = new UpdateCourseHandler($repo);
        $command = new UpdateCourseCommand($courseId, 'Nom Nou');

        $handler->handle($command);

        $this->assertEquals('Nom Nou', $course->name());
    }

    public function test_it_should_throw_exception_if_course_to_update_not_found(): void
    {
        $repo = $this->createMock(CourseRepository::class);
        $repo->method('find')->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("El curs no existeix.");

        $handler = new UpdateCourseHandler($repo);
        $handler->handle(new UpdateCourseCommand('id-inexistent', 'Qualsevol Nom'));
    }
}