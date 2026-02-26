<?php

namespace App\Infrastructure\Controller;

use App\Infrastructure\Persistence\Database;
use App\Domain\Student\SqlStudentRepository;
use App\Domain\Course\SqlCourseRepository;
use App\Domain\Teacher\SqlTeacherRepository;

final class HomeController {
    public function index(): void  { 
        include __DIR__ . '/../../templates/home.php'; 
    }
}