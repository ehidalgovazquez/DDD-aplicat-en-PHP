<?php

namespace App\Domain\Subject;

interface SubjectRepository
{
    public function find(SubjectId $id): ?Subject;
    public function save(Subject $subject): void;
    public function update(Subject $subject): void;
    public function delete(SubjectId $id): void;
}