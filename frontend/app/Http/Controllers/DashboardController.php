<?php

namespace App\Http\Controllers;

use App\Services\BackendApiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected BackendApiService $api
    ) {}

    public function index()
    {
        $courses = $this->api->getCourses();
        $students = $this->api->getStudents();
        $teachers = $this->api->getTeachers();
        $subjects = $this->api->getSubjects();


        return view('dashboard', compact('courses', 'students', 'teachers', 'subjects'));
    }
}