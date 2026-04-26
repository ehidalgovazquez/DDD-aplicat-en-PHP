<?php

namespace App\Application\Student\DeleteStudent;

final class DeleteStudentCommand {
    public function __construct(
        public readonly string $id
    ) {}
}