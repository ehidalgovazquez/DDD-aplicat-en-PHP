<?php

namespace App\Domain\Course;

use App\Domain\Course\Course;
use App\Domain\Course\CourseId;
use App\Domain\Course\CourseRepository;
use PDO;

final class SqlCourseRepository implements CourseRepository
{
    public function __construct(private PDO $connection) {}

    public function find(CourseId $id): ?Course {
        $stmt = $this->connection->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute(['id' => $id->value()]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Course(new CourseId($row['id']), $row['name']);
    }

    public function save(Course $course): void {
        $sql = "INSERT INTO courses (id, name) VALUES (:id, :name)";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'id'    => $course->id()->value(),
            'name'  => $course->name()
        ]);
    }

    public function update(Course $course): void {
        $stmt = $this->connection->prepare("UPDATE courses SET name = :name WHERE id = :id");
        $stmt->execute([
            'id'   => $course->id()->value(),
            'name' => $course->name()
        ]);
    }

    public function delete(CourseId $id): void {
        $stmt = $this->connection->prepare("DELETE FROM courses WHERE id = :id");
        $stmt->execute(['id' => $id->value()]);
    }

    public function all(): array {
        $stmt = $this->connection->query("SELECT * FROM courses");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $courses = [];
        foreach ($rows as $row) {
            $courses[] = new Course(
                new CourseId($row['id']),
                $row['name']
            );
        }

        return $courses;
    }
}