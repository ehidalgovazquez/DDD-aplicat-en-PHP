<?php

namespace App\Http\Controllers;

use App\Services\BackendApiService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        protected BackendApiService $api
    ) {}

    public function index() {
        $courses = $this->api->getCourses();
        return view('courses.index', compact('courses'));
    }

    public function create() {
        $courses = $this->api->getCourses();
        return view('courses.create', compact('courses'));
    }

    public function store(Request $request) {
        $existsCourse = collect($this->api->getCourses()['data'] ?? [])->firstWhere('id', $request->input('id'));
        if ($existsCourse) {
            return back()->withErrors(['id' => 'El ID "' . $request->input('id') . '" del curso ya existe.'])->withInput();
        }

        $response = $this->api->createCourse($request->all());

        if ($response->successful()) {
            return redirect()->route('courses.index')->with('success', 'Curso creado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al crear el curso ' . $response->body()])->withInput();
        }
    }

    public function destroy(string $id) {
        $response = $this->api->deleteCourse($id);

        if ($response->successful()) {
            return redirect()->route('courses.index')->with('success', 'Curso eliminado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al eliminar el curso.']);
        }
    }

    public function edit(string $id) {
        $course = collect($this->api->getCourses()['data'] ?? [])->firstWhere('id', $id);
        if (!$course) {
            return redirect()->route('courses.index')->withErrors(['error' => 'Curso no encontrado.']);
        }

        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, string $id) {
        $existsCourse = collect($this->api->getCourses()['data'] ?? [])->firstWhere('id', $id);
        if (!$existsCourse) {
            return back()->withErrors(['id' => 'El curso no existe.'])->withInput();
        }

        $response = $this->api->updateCourse($id, $request->all());

        if ($response->successful()) {
            return redirect()->route('courses.index')->with('success', 'Curso actualizado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al actualizar el curso.'])->withInput();
        }
    }

    public function show(string $id) {
        $course = collect($this->api->getCourses()['data'] ?? [])->firstWhere('id', $id);
        if (!$course) {
            return redirect()->route('courses.index')->withErrors(['error' => 'Curso no encontrado.']);
        }

        return view('courses.show', compact('course'));
    }
}