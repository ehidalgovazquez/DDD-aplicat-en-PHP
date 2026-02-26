<?php

namespace App\Domain\Subject;

use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Teacher\TeacherId;
use App\Domain\Subject\SubjectRepository;
use PDO;

final class SqlSubjectRepository implements SubjectRepository
{
    public function __construct(private readonly PDO $connection) {}

    public function find(SubjectId $id): ?Subject
    {
        $stmt = $this->connection->prepare("SELECT * FROM subjects WHERE id = :id");
        $stmt->execute(['id' => $id->value()]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        $subject = new Subject(
            new SubjectId($row['id']),
            $row['name']
        );

        if ($row['teacher_id'] !== null) {
            $subject->assignTeacher(new TeacherId($row['teacher_id']));
        }

        return $subject;
    }

    public function save(Subject $subject): void {
        $stmt = $this->connection->prepare(
            "INSERT INTO subjects (id, name, teacher_id) VALUES (:id, :name, :teacher_id)"
        );
        
        $stmt->execute([
            'id'         => $subject->id()->value(),
            'name'       => $subject->name(),
            'teacher_id' => $subject->teacherId()?->value()
        ]);
    }

    public function update(Subject $subject): void {
        $stmt = $this->connection->prepare(
            "UPDATE subjects SET name = :name, teacher_id = :teacher_id WHERE id = :id"
        );
        
        $stmt->execute([
            'id'         => $subject->id()->value(),
            'name'       => $subject->name(),
            'teacher_id' => $subject->teacherId()?->value()
        ]);
    }

    public function delete(SubjectId $id): void {
        $stmt = $this->connection->prepare("DELETE FROM subjects WHERE id = :id");
        $stmt->execute(['id' => $id->value()]);
    }

    public function all(): array
    {
        $stmt = $this->connection->query("SELECT * FROM subjects");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function (array $row) {
            $subject = new Subject(
                new SubjectId($row['id']),
                $row['name']
            );
            if ($row['teacher_id'] !== null) {
                $subject->assignTeacher(new TeacherId($row['teacher_id']));
            }
            return $subject;
        }, $rows);
    }
}