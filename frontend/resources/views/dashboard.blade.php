<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Portal de Gestión Educativa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Listado de Estudiantes</h3>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Curso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students['data'] ?? [] as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $student['id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $student['name'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $student['email'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $student['course_id'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">No se encontraron estudiantes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-4">
                <h3 class="text-lg font-bold mb-4">Listado de Cursos</h3>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses['data'] ?? [] as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $course['id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $course['name'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center py-4">No se encontraron cursos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-4">
                <h3 class="text-lg font-bold mb-4">Listado de Materias</h3>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Profesor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects['data'] ?? [] as $subject)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $subject['id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $subject['name'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $subject['teacher_id'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-4">No se encontraron materias.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-4">
                <h3 class="text-lg font-bold mb-4">Listado de Profesores</h3>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers['data'] ?? [] as $teacher)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $teacher['id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $teacher['name'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $teacher['email'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center py-4">No se encontraron profesores.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>