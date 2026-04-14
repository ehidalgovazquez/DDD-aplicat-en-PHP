<?php

namespace App\Domain\Subject;

use Doctrine\ORM\EntityManagerInterface;

final class SqlSubjectRepository implements SubjectRepository 
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function find(SubjectId $id): ?Subject {
        return $this->entityManager->find(Subject::class, $id->value());
    }

    public function save(Subject $subject): void {
        $this->entityManager->persist($subject);
        $this->entityManager->flush();
    }

    public function update(Subject $subject): void {
        $this->entityManager->persist($subject);
        $this->entityManager->flush();
    }

    public function delete(SubjectId $id): void {
        $subjectReference = $this->entityManager->getReference(Subject::class, $id->value());
        
        if ($subjectReference !== null) {
            $this->entityManager->remove($subjectReference);
            $this->entityManager->flush();
        }
    }

    public function all(): array {
            return $this->entityManager->getRepository(Subject::class)->findAll();
    }
}