<?php
    use App\Infrastructure\Controller\StudentApiController;
    use App\Infrastructure\Controller\SubjectApiController;
    use App\Infrastructure\Controller\CourseApiController;use App\Infrastructure\Controller\TeacherApiController;    
    return [
        // Student API routes
        [
            'method' => 'GET',
            'path' => '/api/students',
            'handler' => [StudentApiController::class, 'index']
        ],
        [
            'method' => 'GET',
            'path' => '/api/students/{id}',
            'handler' => [StudentApiController::class, 'show']
        ],
        [
            'method'=> 'POST',
            'path' => '/api/students',
            'handler' => [StudentApiController::class, 'store']
        ],
        [
            'method'=> 'PUT',
            'path' => '/api/students/{id}',
            'handler' => [StudentApiController::class, 'update']
        ],
        [
            'method'=> 'DELETE',
            'path' => '/api/students/{id}',
            'handler' => [StudentApiController::class, 'delete']
        ],
        [
            'method' => 'POST',
            'path' => '/api/students/{id}/enroll',
            'handler' => [StudentApiController::class, 'enroll']
        ],
        [
            'method' => 'POST',
            'path' => '/api/students/{id}/unenroll',
            'handler' => [StudentApiController::class, 'unenroll']
        ],
        
        // Course API routes
        [
            'method' => 'GET',
            'path' => '/api/courses',
            'handler' => [CourseApiController::class, 'index']
        ],
        [
            'method' => 'GET',
            'path' => '/api/courses/{id}',
            'handler' => [CourseApiController::class, 'show']
        ],
        [
            'method' => 'POST',
            'path' => '/api/courses',
            'handler' => [CourseApiController::class, 'store']
        ],
        [
            'method' => 'PUT',
            'path' => '/api/courses/{id}',
            'handler' => [CourseApiController::class, 'update']
        ],
        [
            'method' => 'DELETE',
            'path' => '/api/courses/{id}',
            'handler' => [CourseApiController::class, 'delete']
        ],
        
        // Teacher API routes
        [
            'method' => 'GET',
            'path' => '/api/teachers',
            'handler' => [TeacherApiController::class, 'index']
        ],
        [
            'method' => 'GET',
            'path' => '/api/teachers/{id}',
            'handler' => [TeacherApiController::class, 'show']
        ],
        [
            'method' => 'POST',
            'path' => '/api/teachers',
            'handler' => [TeacherApiController::class, 'store']
        ],
        [
            'method' => 'PUT',
            'path' => '/api/teachers/{id}',
            'handler' => [TeacherApiController::class, 'update']
        ],
        [
            'method' => 'DELETE',
            'path' => '/api/teachers/{id}',
            'handler' => [TeacherApiController::class, 'delete']
        ],
        
        // Subject API routes
        [
            'method' => 'GET',
            'path' => '/api/subjects',
            'handler' => [SubjectApiController::class, 'index']
        ],
        [
            'method' => 'GET',
            'path' => '/api/subjects/{id}',
            'handler' => [SubjectApiController::class, 'show']
        ],
        [
            'method'=> 'POST',
            'path' => '/api/subjects',
            'handler' => [SubjectApiController::class, 'store']
        ],
        [
            'method'=> 'PUT',
            'path' => '/api/subjects/{id}',
            'handler' => [SubjectApiController::class, 'update']
        ],
        [
            'method'=> 'DELETE',
            'path' => '/api/subjects/{id}',
            'handler' => [SubjectApiController::class, 'delete']
        ],
        [
            'method' => 'POST',
            'path' => '/api/subjects/{id}/assign-teacher',
            'handler' => [SubjectApiController::class, 'assignTeacher']
        ],
        [
            'method' => 'POST',
            'path' => '/api/subjects/{id}/unassign-teacher',
            'handler' => [SubjectApiController::class, 'unassignTeacher']
        ]
    ];