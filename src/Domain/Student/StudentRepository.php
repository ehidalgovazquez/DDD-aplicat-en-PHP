<?php

namespace App\Domain\Student;

interface StudentRepository
{
    public function find(StudentId $id): ?Student;
    public function save(Student $student): void;
}