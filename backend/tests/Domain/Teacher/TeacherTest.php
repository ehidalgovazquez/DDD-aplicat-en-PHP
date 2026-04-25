<?php

namespace Tests\Domain\Teacher;

use PHPUnit\Framework\TestCase;
use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;

class TeacherTest extends TestCase
{
    public function test_teacher_can_be_created(): void
    {
        $id = new TeacherId('teach-1');
        $name = 'Profe DDD';
        $email = 'profe@example.com';

        $teacher = new Teacher($id, $name, $email);

        $this->assertEquals($id, $teacher->id());
        $this->assertEquals($name, $teacher->name());
        $this->assertEquals($email, $teacher->email());
    }
}