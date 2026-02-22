<?php

namespace Tests\Application\Course;

use PHPUnit\Framework\TestCase;
use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use App\Application\Course\CreateCourseHandler;
use App\Application\Course\CreateCourse\CreateCourseCommand;

final class CreateCourseTest extends TestCase
{
    public function test_it_should_create_a_course(): void
    {
        $courseId = 'course-1';
        $name = 'PHP Estructural';

        $course = new Course(new CourseId($courseId), $name);

        $courseRepository = $this->createMock(CourseRepository::class);
        $courseRepository->method('find')->willReturn($course); 
        $courseRepository->expects($this->once())->method('save');

        $handler = new CreateCourseHandler($courseRepository);
        $command = new CreateCourseCommand($courseId, $name);

        $handler->handle($command);
        
        $this->assertTrue(true);
    }
}