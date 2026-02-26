<?php

namespace App\Application\Teacher\DeleteTeacher;

final class DeleteTeacherCommand {
    public function __construct(public readonly string $id) {}
}