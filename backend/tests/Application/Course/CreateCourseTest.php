<?php

namespace Tests\Application\Course;

use PHPUnit\Framework\TestCase;
use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use App\Application\Course\CreateCourse\CreateCourseCommand;
use App\Application\Course\CreateCourse\CreateCourseHandler;

class CreateCourseTest extends TestCase
{
    public function test_it_should_create_a_course_successfully(): void
    {
        $repo = $this->createMock(CourseRepository::class);
        
        $repo->method('find')->willReturn(null);
        $repo->expects($this->once())->method('save');

        $handler = new CreateCourseHandler($repo);
        $handler->handle(new CreateCourseCommand('course-1', 'Nuevo Curso'));
        
        $this->assertTrue(true);
    }

    public function test_it_should_throw_exception_if_course_id_already_exists(): void 
    {
        $courseId = 'course-1';
        $existingCourse = new Course(new CourseId($courseId), 'PHP Existente');

        $courseRepository = $this->createMock(CourseRepository::class);
        
        $courseRepository->method('find')->willReturn($existingCourse);

        $handler = new CreateCourseHandler($courseRepository);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage("El curs amb ID 'course-1' ja existeix.");

        $handler->handle(new CreateCourseCommand($courseId, 'Nuevo Curso'));
    }
}