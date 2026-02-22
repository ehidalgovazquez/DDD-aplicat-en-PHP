<?php

namespace App\Domain\Teacher;

final class TeacherId
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new \InvalidArgumentException('TeacherId cannot be empty');
        }
    }
    
    public static function generate(): self
    {
        return new self(
            uniqid('teacher_', true)
        );
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}