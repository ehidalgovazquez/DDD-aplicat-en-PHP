<?php

namespace App\Domain\Teacher;

interface TeacherRepository
{
    public function find(TeacherId $id): ?Teacher;
    public function save(Teacher $teacher): void;
    public function update(Teacher $teacher): void;
    public function delete(TeacherId $id): void;
}