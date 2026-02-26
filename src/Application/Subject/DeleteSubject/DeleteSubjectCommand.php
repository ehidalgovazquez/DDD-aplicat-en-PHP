<?php

namespace App\Application\Subject\DeleteSubject;

final class DeleteSubjectCommand
{
    public function __construct(
        public readonly string $id
    ) {}
}