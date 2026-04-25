<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class BackendApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        // Asegúrate de tener BACKEND_API_URL=http://localhost:8000 en tu .env del frontend
        $this->baseUrl = config('services.backend.url', env('BACKEND_API_URL', 'http://localhost:8000'));
    }

    /**
     * Centraliza las peticiones para añadir cabeceras y manejo de errores básico
     */
    private function request(string $method, string $endpoint, array $data = []): Response
    {
        $url = "{$this->baseUrl}/{$endpoint}";
        
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->send($method, $url, [
            'json' => $data
        ]);

        if ($response->failed()) {
            Log::error("Error en llamada al Backend: {$method} {$url}", [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
        }

        return $response;
    }

    // ==========================================
    // MÉTODOS PARA CURSOS (CourseApiController)
    // ==========================================

    public function getCourses(): array
    {
        return $this->request('GET', 'courses')->json() ?? [];
    }

    public function getCourse(string $id): array
    {
        return $this->request('GET', "courses/{$id}")->json() ?? [];
    }

    public function createCourse(array $data): Response
    {
        // En tu backend es POST /courses/create
        return $this->request('POST', 'courses/create', $data);
    }

    public function updateCourse(string $id, array $data): Response
    {
        // En tu backend es POST /courses/update/{id}
        return $this->request('POST', "courses/update/{$id}", $data);
    }

    public function deleteCourse(string $id): Response
    {
        // En tu backend es POST /courses/delete/{id}
        return $this->request('POST', "courses/delete/{$id}");
    }

    // ==========================================
    // MÉTODOS PARA ESTUDIANTES (StudentApiController)
    // ==========================================

    public function getStudents(): array
    {
        return $this->request('GET', 'students')->json() ?? [];
    }

    public function createStudent(array $data): Response
    {
        return $this->request('POST', 'students/create', $data);
    }

    public function updateStudent(string $id, array $data): Response
    {
        return $this->request('POST', "students/update/{$id}", $data);
    }

    public function deleteStudent(string $id): Response
    {
        return $this->request('POST', "students/delete/{$id}");
    }

    public function enrollStudent(string $studentId, string $courseId): Response
    {
        return $this->request('POST', 'students/enroll', [
            'student_id' => $studentId,
            'course_id' => $courseId
        ]);
    }

    public function unenrollStudent(string $studentId, string $courseId): Response
    {
        return $this->request('POST', 'students/unenroll', [
            'student_id' => $studentId,
            'course_id' => $courseId
        ]);
    }

    // ==========================================
    // MÉTODOS PARA ASIGNATURAS (SubjectApiController)
    // ==========================================

    public function getSubjects(): array
    {
        return $this->request('GET', 'subjects')->json() ?? [];
    }

    public function createSubject(array $data): Response
    {
        return $this->request('POST', 'subjects/create', $data);
    }

    public function updateSubject(string $id, array $data): Response
    {
        return $this->request('POST', "subjects/update/{$id}", $data);
    }

    public function deleteSubject(string $id): Response
    {
        return $this->request('POST', "subjects/delete/{$id}");
    }

    public function assignTeacher(string $subjectId, string $teacherId): Response
    {
        return $this->request('POST', 'subjects/assign-teacher', [
            'subject_id' => $subjectId,
            'teacher_id' => $teacherId
        ]);
    }

    // ==========================================
    // MÉTODOS PARA PROFESORES (TeacherApiController)
    // ==========================================

    public function getTeachers(): array
    {
        return $this->request('GET', 'teachers')->json() ?? [];
    }

    public function createTeacher(array $data): Response
    {
        return $this->request('POST', 'teachers/create', $data);
    }

    public function updateTeacher(string $id, array $data): Response
    {
        return $this->request('POST', "teachers/update/{$id}", $data);
    }

    public function deleteTeacher(string $id): Response
    {
        return $this->request('POST', "teachers/delete/{$id}");
    }
}