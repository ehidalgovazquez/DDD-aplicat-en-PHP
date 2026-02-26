<?php
session_start(); // Fundamental para que los datos no se borren

require __DIR__.'/vendor/autoload.php';

use App\Infrastructure\Http\Request;
use App\Infrastructure\Controller\TeacherController;
use App\Infrastructure\Controller\SubjectController;
use App\Infrastructure\Controller\StudentController;
use App\Infrastructure\Controller\CourseController;
use App\Infrastructure\Controller\HomeController;

$pdo = new PDO('sqlite:' . __DIR__ . '/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$request = new Request();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$teacherController = new TeacherController();
$subjectController = new SubjectController();
$studentController = new StudentController();
$courseController = new CourseController();
$homeController = new HomeController();

match ($path) {
    '/', '/index.php'   => $homeController->index(),
    '/teacher'          => $teacherController->index(),
    '/teacher/create'   => $teacherController->create(),
    '/teacher/store'    => $teacherController->store($request),
    '/teacher/edit'     => $teacherController->edit($request),
    '/teacher/update'   => $teacherController->update($request),
    '/teacher/delete'   => $teacherController->delete($request),

    '/subject'          => $subjectController->index(),
    '/subject/create'   => $subjectController->create(),
    '/subject/store'    => $subjectController->store($request),
    '/subject/assign'   => $subjectController->assignTeacher($request),
    '/subject/edit'     => $subjectController->edit($request),
    '/subject/update'   => $subjectController->update($request),
    '/subject/delete'   => $subjectController->delete($request),
    

    '/student'          => $studentController->index(),
    '/student/create'   => $studentController->create(),
    '/student/store'    => $studentController->store($request),
    '/student/enroll'   => $studentController->enroll($request),
    '/student/edit'     => $studentController->edit($request),
    '/student/update'   => $studentController->update($request),
    '/student/delete'   => $studentController->delete($request),

    '/course'           => $courseController->index(),
    '/course/create'    => $courseController->create(),
    '/course/store'     => $courseController->store($request),
    '/course/edit'      => $courseController->edit($request),
    '/course/update'    => $courseController->update($request),
    '/course/delete'    => $courseController->delete($request),
    
    default             => http_response_code(404)
};