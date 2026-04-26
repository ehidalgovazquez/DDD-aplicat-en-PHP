<?php

namespace Tests\Application\Course;

use PHPUnit\Framework\TestCase;
use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use App\Application\Course\DeleteCourse\DeleteCourseHandler;
use App\Application\Course\DeleteCourse\DeleteCourseCommand;

final class DeleteCourseTest extends TestCase
{
    public function test_it_should_delete_a_course_successfully(): void
    {
        $courseId = 'course-1';
        $course = new Course(new CourseId($courseId), 'Curs a eliminar');
        
        $repo = $this->createMock(CourseRepository::class);
        
        $repo->method('find')->willReturn($course);
        $repo->expects($this->once())->method('delete');

        $handler = new DeleteCourseHandler($repo);
        $handler->handle(new DeleteCourseCommand($courseId));
        
        $this->assertTrue(true);
    }

    public function test_it_should_throw_exception_if_course_to_delete_not_found(): void
    {
        $repo = $this->createMock(CourseRepository::class);
        $repo->method('find')->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("No es pot eliminar un curs que no existeix.");

        $handler = new DeleteCourseHandler($repo);
        $handler->handle(new DeleteCourseCommand('id-fantasma'));
    }
}