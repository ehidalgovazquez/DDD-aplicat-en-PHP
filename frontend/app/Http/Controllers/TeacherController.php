<?php

namespace App\Http\Controllers;

use App\Services\BackendApiService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function __construct(
        protected BackendApiService $api
    ) {}

    public function index() {
        $teachers = $this->api->getTeachers();
        return view('teachers.index', compact('teachers'));
    }

    public function create() {
        $teachers = $this->api->getTeachers();
        return view('teachers.create', compact('teachers'));
    }

    public function store(Request $request) {
        $existsTeacher = collect($this->api->getTeachers()['data'] ?? [])->firstWhere('id', $request->input('id'));
        if ($existsTeacher) {
            return back()->withErrors(['id' => 'El ID "' . $request->input('id') . '" del profesor ya existe.'])->withInput();
        }

        $response = $this->api->createTeacher($request->all());

        if ($response->successful()) {
            return redirect()->route('teachers.index')->with('success', 'Profesor creado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al crear el profesor ' . $response->body()])->withInput();
        }
    }

    public function destroy(string $id) {
        $response = $this->api->deleteTeacher($id);

        if ($response->successful()) {
            return redirect()->route('teachers.index')->with('success', 'Profesor eliminado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al eliminar el profesor.']);
        }
    }

    public function edit(string $id) {
        $teacher = collect($this->api->getTeachers()['data'] ?? [])->firstWhere('id', $id);
        if (!$teacher) {
            return redirect()->route('teachers.index')->withErrors(['error' => 'Profesor no encontrado.']);
        }

        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, string $id) {
        $existsTeacher = collect($this->api->getTeachers()['data'] ?? [])->firstWhere('id', $id);
        if (!$existsTeacher) {
            return back()->withErrors(['id' => 'El profesor no existe.'])->withInput();
        }

        $response = $this->api->updateTeacher($id, $request->all());

        if ($response->successful()) {
            return redirect()->route('teachers.index')->with('success', 'Profesor actualizado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al actualizar el profesor.'])->withInput();
        }
    }

    public function show(string $id) {
        $teacher = collect($this->api->getTeachers()['data'] ?? [])->firstWhere('id', $id);
        if (!$teacher) {
            return redirect()->route('teachers.index')->withErrors(['error' => 'Profesor no encontrado.']);
        }

        return view('teachers.show', compact('teacher'));
    }
}