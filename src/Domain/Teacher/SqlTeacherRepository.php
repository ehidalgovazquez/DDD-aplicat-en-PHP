<?php

namespace App\Domain\Teacher;

use Doctrine\ORM\EntityManagerInterface;

final class SqlTeacherRepository implements TeacherRepository 
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function find(TeacherId $id): ?Teacher {
        return $this->entityManager->find(Teacher::class, $id->value());
    }

    public function save(Teacher $teacher): void {
        $this->entityManager->persist($teacher);
        $this->entityManager->flush();
    }

    public function update(Teacher $teacher): void {
        $this->entityManager->persist($teacher);
        $this->entityManager->flush();
    }

    public function delete(TeacherId $id): void {
        $teacherReference = $this->entityManager->getReference(Teacher::class, $id->value());
        
        if ($teacherReference !== null) {
            $this->entityManager->remove($teacherReference);
            $this->entityManager->flush();
        }
    }

    public function all(): array {
            return $this->entityManager->getRepository(Teacher::class)->findAll();
    }
}