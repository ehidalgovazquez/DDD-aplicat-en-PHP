<?php

namespace App\Domain\Teacher;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;
use App\Domain\Teacher\TeacherRepository;
use PDO;

final class SqlTeacherRepository implements TeacherRepository
{
    public function __construct(private readonly PDO $connection) {}

    public function find(TeacherId $id): ?Teacher
    {
        $stmt = $this->connection->prepare("SELECT * FROM teachers WHERE id = :id");
        $stmt->execute(['id' => $id->value()]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Teacher(
            new TeacherId($row['id']),
            $row['name'],
            $row['email']
        );
    }

    public function save(Teacher $teacher): void
    {
        $stmt = $this->connection->prepare(
            "INSERT INTO teachers (id, name, email) VALUES (:id, :name, :email)"
        );
        
        $stmt->execute([
            'id'    => $teacher->id()->value(),
            'name'  => $teacher->name(),
            'email' => $teacher->email()
        ]);
    }

    public function update(Teacher $teacher): void
    {
        $stmt = $this->connection->prepare(
            "UPDATE teachers SET name = :name, email = :email WHERE id = :id"
        );
        
        $stmt->execute([
            'id'    => $teacher->id()->value(),
            'name'  => $teacher->name(),
            'email' => $teacher->email()
        ]);
    }

    public function delete(TeacherId $id): void
    {
        $stmt = $this->connection->prepare("DELETE FROM teachers WHERE id = :id");
        $stmt->execute(['id' => $id->value()]);
    }

    public function all(): array
    {
        $stmt = $this->connection->query("SELECT * FROM teachers");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function (array $row) {
            return new Teacher(
                new TeacherId($row['id']),
                $row['name'],
                $row['email']
            );
        }, $rows);
    }
}