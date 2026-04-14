<?php
    use App\Infrastructure\Controller\StudentApiController;
    
    return [
        [
            'method' => 'GET',
            'path' => '/api/students',
            'handler' => [StudentApiController::class, 'index']
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
        ]
    ];