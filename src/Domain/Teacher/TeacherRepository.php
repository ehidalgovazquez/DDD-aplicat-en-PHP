<?php

namespace App\Domain\Teacher;

interface TeacherRepository
{
    public function find(TeacherId $id): ?Teacher;
    public function save(Teacher $teacher): void;
}