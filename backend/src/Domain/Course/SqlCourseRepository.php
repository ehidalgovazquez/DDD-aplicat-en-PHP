<?php

namespace App\Domain\Course;

use Doctrine\ORM\EntityManagerInterface;

final class SqlCourseRepository implements CourseRepository {
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    public function find(CourseId $id): ?Course {
        return $this->entityManager->find(Course::class, $id->value());
    }

    public function save(Course $course): void {
        $this->entityManager->persist($course);
        $this->entityManager->flush();
    }

    public function update(Course $course): void {
        $this->entityManager->persist($course);
        $this->entityManager->flush();
    }

    public function delete(CourseId $id): void {
        $courseReference = $this->entityManager->getReference(Course::class, $id->value());
        
        if ($courseReference !== null) {
            $this->entityManager->remove($courseReference);
            $this->entityManager->flush();
        }
    }

    public function all(): array {
            return $this->entityManager->getRepository(Course::class)->findAll();
    }
}