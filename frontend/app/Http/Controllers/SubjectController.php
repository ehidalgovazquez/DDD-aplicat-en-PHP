<?php

namespace App\Http\Controllers;

use App\Services\BackendApiService;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function __construct(
        protected BackendApiService $api
    ) {}

    public function index() {
        $subjects = $this->api->getSubjects();
        return view('subjects.index', compact('subjects'));
    }

    public function create() {
        $teachers = $this->api->getTeachers();
        return view('subjects.create', compact('teachers'));
    }

    public function store(Request $request) {
        $existsSubject = collect($this->api->getSubjects()['data'] ?? [])->firstWhere('id', $request->input('id'));
        if ($existsSubject) {
            return back()->withErrors(['id' => 'El ID "' . $request->input('id') . '" de la materia ya existe.'])->withInput();
        }

        $response = $this->api->createSubject($request->all());
        
        if ($response->successful()) {
            return redirect()->route('subjects.index')->with('success', 'Materia creada exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al crear la materia ' . $response->body()])->withInput();
        }
    }

    public function destroy(string $id) {
        $response = $this->api->deleteSubject($id);

        if ($response->successful()) {
            return redirect()->route('subjects.index')->with('success', 'Materia eliminada exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al eliminar la materia.']);
        }
    }

    public function assignteacher(Request $request) {
        $existsSubject = collect($this->api->getSubjects()['data'] ?? [])->firstWhere('id', $request->input('subject_id'));
        if (!$existsSubject) {
            return back()->withErrors(['subject_id' => 'El ID de la materia no existe.'])->withInput();
        }
        $existsTeacher = collect($this->api->getTeachers()['data'] ?? [])->firstWhere('id', $request->input('teacher_id'));
        if (!$existsTeacher) {
            return back()->withErrors(['teacher_id' => 'El ID del profesor no existe.'])->withInput();
        }

        $data = $request->all();

        $response = $this->api->assignTeacher($data['subject_id'], $data['teacher_id']);

        if ($response->successful()) {
            return redirect()->route('subjects.index')->with('success', 'Profesor asignado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al asignar el profesor.'])->withInput();
        }
    }

    public function unassignteacher(Request $request) {
        $existsSubject = collect($this->api->getSubjects()['data'] ?? [])->firstWhere('id', $request->input('subject_id'));
        if (!$existsSubject) {
            return back()->withErrors(['subject_id' => 'El ID de la materia no existe.'])->withInput();
        }

        $existsTeacher = collect($this->api->getTeachers()['data'] ?? [])->firstWhere('id', $request->input('teacher_id'));
        if (!$existsTeacher) {
            return back()->withErrors(['teacher_id' => 'El ID del profesor no existe.'])->withInput();
        }

        $data = $request->all();
        $response = $this->api->unassignTeacher($data['subject_id'], $data['teacher_id']);

        if ($response->successful()) {
            return redirect()->route('subjects.index')->with('success', 'Profesor desasignado exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al desasignar el profesor.'])->withInput();
        }
    }

    public function edit(string $id) {
        $subject = collect($this->api->getSubjects()['data'] ?? [])->firstWhere('id', $id);
        if (!$subject) {
            return redirect()->route('subjects.index')->withErrors(['error' => 'Materia no encontrada.']);
        }

        $teachers = $this->api->getTeachers();

        return view('subjects.edit', compact('subject', 'teachers'));
    }

    public function update(Request $request, string $id) {
        $existsSubject = collect($this->api->getSubjects()['data'] ?? [])->firstWhere('id', $id);
        if (!$existsSubject) {
            return back()->withErrors(['id' => 'La materia no existe.'])->withInput();
        }

        $response = $this->api->updateSubject($id, $request->all());

        if ($response->successful()) {
            return redirect()->route('subjects.index')->with('success', 'Materia actualizada exitosamente.');
        } else {
            return back()->withErrors(['error' => 'Error al actualizar la materia.'])->withInput();
        }
    }

    public function show(string $id) {
        $subject = collect($this->api->getSubjects()['data'] ?? [])->firstWhere('id', $id);
        if (!$subject) {
            return redirect()->route('subjects.index')->withErrors(['error' => 'Materia no encontrada.']);
        }

        return view('subjects.show', compact('subject'));
    }
}