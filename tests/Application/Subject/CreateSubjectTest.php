<?php

namespace Tests\Application\Subject;

use PHPUnit\Framework\TestCase;
use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;
use App\Domain\Subject\SubjectRepository;
use App\Application\Subject\CreateSubjectHandler;
use App\Application\Subject\CreateSubject\CreateSubjectCommand;

final class CreateSubjectTest extends TestCase
{
    public function test_it_should_create_a_subject(): void
    {
        $subjectId = 'subject-1';
        $name = 'Matemáticas';

        $subject = new Subject(new SubjectId($subjectId), $name);

        $subjectRepository = $this->createMock(SubjectRepository::class);
        $subjectRepository->method('find')->willReturn($subject); 
        $subjectRepository->expects($this->once())->method('save');

        $handler = new CreateSubjectHandler($subjectRepository);
        $command = new CreateSubjectCommand($subjectId, $name);

        $handler->handle($command);
        
        $this->assertTrue(true);
    }
}