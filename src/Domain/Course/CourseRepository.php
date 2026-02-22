<?php

namespace App\Domain\Course;

interface CourseRepository
{
    public function find(CourseId $id): ?Course;
    public function save(Course $course): void;
}