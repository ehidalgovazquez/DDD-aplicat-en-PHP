<?php

namespace App\Domain\Student;

use App\Domain\Student\Student;
use App\Domain\Student\StudentId;
use App\Domain\Student\StudentRepository;
use App\Domain\Course\CourseId;
use PDO;

final class SqlStudentRepository implements StudentRepository {
    public function __construct(private PDO $connection) {}

    public function find(StudentId $id): ?Student {
        $stmt = $this->connection->prepare("SELECT * FROM students WHERE id = :id");
        $stmt->execute(['id' => $id->value()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $student = new Student(
            new StudentId($row['id']),
            $row['name'],
            $row['email']
        );

        if (!empty($row['course_id'])) {
            $student->enrollInto(new CourseId($row['course_id']));
        }

        return $student;
    }

    public function save(Student $student): void {
        $sql = "INSERT INTO students (id, name, email, course_id) VALUES (:id, :name, :email, :course_id)";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'id'        => $student->id()->value(),
            'name'      => $student->name(),
            'email'     => $student->email(),
            'course_id' => $student->courseId()?->value()
        ]);
    }

    public function update(Student $student): void {
        $sql = "UPDATE students SET name = :name, email = :email, course_id = :course_id WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'id'        => $student->id()->value(),
            'name'      => $student->name(),
            'email'     => $student->email(),
            'course_id' => $student->courseId()?->value()
        ]);
    }

    public function delete(StudentId $id): void {
        $stmt = $this->connection->prepare("DELETE FROM students WHERE id = :id");
        $stmt->execute(['id' => $id->value()]);
    }

    public function all(): array
    {
        $stmt = $this->connection->query("SELECT * FROM students");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function($row) {
            $student = new Student(
                new StudentId($row['id']),
                $row['name'],
                $row['email']
            );

            if (!empty($row['course_id'])) {
                $student->enrollInto(new CourseId($row['course_id']));
            }

            return $student;
        }, $rows);
    }
}