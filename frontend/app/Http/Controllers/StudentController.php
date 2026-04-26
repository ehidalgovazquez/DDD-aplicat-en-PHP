<?php

namespace App\Http\Controllers;

use App\Services\BackendApiService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(
        protected BackendApiService $api
    ) {}

    public function index() {
        $students = $this->api->getStudents();
        return view('students.index', compact('students'));
    }

    public function create() {
        $courses = $this->api->getCourses();
        return view('students.create', compact('courses'));
    }

    public function store(Request $request) {
        $existsStudent = collect($this->api->getStudents()['data'] ?? [])->firstWhere('id', $request->input('id'));
        if ($existsStudent) {
            return back()->withErrors(['id' => 'El ID "' . $request->input('id') . '" del estudiante ya existe.'])->withInput();
        }

        $response = $this->api->createStudent($request->all());
        
        if ($response->successful()) {
            return redirect()->route('students.index')->with('success', 'Estudiante creado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al crear el estudiante ' . $response->body()])->withInput();
        }
    }

    public function destroy(string $id) {
        $response = $this->api->deleteStudent($id);

        if ($response->successful()) {
            return redirect()->route('students.index')->with('success', 'Estudiante eliminado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al eliminar el estudiante.']);
        }
    }

    public function enroll(Request $request) {
        $existsStudent = collect($this->api->getStudents()['data'] ?? [])->firstWhere('id', $request->input('student_id'));
        if (!$existsStudent) {
            return back()->withErrors(['student_id' => 'El ID del estudiante no existe.'])->withInput();
        }

        $existsCourse = collect($this->api->getCourses()['data'] ?? [])->firstWhere('id', $request->input('course_id'));
        if (!$existsCourse) {
            return back()->withErrors(['course_id' => 'El ID del curso no existe.'])->withInput();
        }

        $data = $request->all();
        $response = $this->api->enrollStudent($data['student_id'], $data['course_id']);

        if ($response->successful()) {
            return redirect()->route('students.index')->with('success', 'Estudiante matriculado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al matricular el estudiante.'])->withInput();
        }
    }

    public function unenroll(Request $request) {
        $existsStudent = collect($this->api->getStudents()['data'] ?? [])->firstWhere('id', $request->input('student_id'));
        if (!$existsStudent) {
            return back()->withErrors(['student_id' => 'El ID del estudiante no existe.'])->withInput();
        }
        $existsCourse = collect($this->api->getCourses()['data'] ?? [])->firstWhere('id', $request->input('course_id'));
        if (!$existsCourse) {
            return back()->withErrors(['course_id' => 'El ID del curso no existe.'])->withInput();
        }

        $data = $request->all();

        $response = $this->api->unenrollStudent($data['student_id'], $data['course_id']);

        if ($response->successful()) {
            return redirect()->route('students.index')->with('success', 'Estudiante desmatriculado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al desmatricular el estudiante.'])->withInput();
        }
    }

    public function edit(string $id) {
        $student = collect($this->api->getStudents()['data'] ?? [])->firstWhere('id', $id);
        if (!$student) {
            return redirect()->route('students.index')->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        $courses = $this->api->getCourses();

        return view('students.edit', compact('student', 'courses'));
    }

    public function update(Request $request, string $id) {
        $existsStudent = collect($this->api->getStudents()['data'] ?? [])->firstWhere('id', $id);
        if (!$existsStudent) {
            return back()->withErrors(['id' => 'El estudiante no existe.'])->withInput();
        }

        $response = $this->api->updateStudent($id, $request->all());

        if ($response->successful()) {
            return redirect()->route('students.index')->with('success', 'Estudiante actualizado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al actualizar el estudiante.'])->withInput();
        }
    }

    public function show(string $id) {
        $student = collect($this->api->getStudents()['data'] ?? [])->firstWhere('id', $id);
        if (!$student) {
            return redirect()->route('students.index')->withErrors(['error' => 'Estudiante no encontrado.']);
        }

        return view('students.show', compact('student'));
    }
}