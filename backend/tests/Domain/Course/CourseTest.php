<?php

namespace Tests\Domain\Course;

use PHPUnit\Framework\TestCase;
use App\Domain\Course\Course;
use App\Domain\Course\CourseId;

class CourseTest extends TestCase
{
    public function test_course_can_be_created(): void 
    {
        $id = new CourseId('course-1');
        $name = 'PHP Estructural';

        $course = new Course($id, $name);

        $this->assertEquals($id, $course->id());
        $this->assertEquals($name, $course->name());
    }

    public function test_it_should_rename_a_course(): void
    {
        $course = new Course(new CourseId('course-1'), 'Nombre Original');
        
        $course->update('Nombre Nuevo');

        $this->assertEquals('Nombre Nuevo', $course->name());
    }
}