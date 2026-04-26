<?php

namespace App\Domain\Student;

use Doctrine\ORM\EntityManagerInterface;

final class SqlStudentRepository implements StudentRepository {
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function find(StudentId $id): ?Student {
        return $this->entityManager->find(Student::class, $id->value());
    }

    public function save(Student $student): void {
        $this->entityManager->persist($student);
        $this->entityManager->flush();
    }

    public function update(Student $student): void {
        $this->entityManager->persist($student);
        $this->entityManager->flush();
    }

    public function delete(StudentId $id): void {
        $studentReference = $this->entityManager->getReference(Student::class, $id->value());
        
        if ($studentReference !== null) {
            $this->entityManager->remove($studentReference);
            $this->entityManager->flush();
        }
    }

    public function all(): array {
            return $this->entityManager->getRepository(Student::class)->findAll();
    }
}