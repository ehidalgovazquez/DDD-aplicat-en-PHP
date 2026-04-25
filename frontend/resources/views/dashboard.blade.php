<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Portal de Gestión Educativa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Cursos disponibles en el Backend DDD</h3>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses['data'] ?? [] as $course)
                            <tr>
                                <td>{{ $course['id'] ?? 'N/A' }}</td>
                                <td>{{ $course['name'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center py-4">No se encontraron cursos.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-4">
                <h3 class="text-lg font-bold mb-4">Estudiantes disponibles en el Backend DDD</h3>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students['data'] ?? [] as $student)
                            <tr>
                                <td>{{ $student['id'] ?? 'N/A' }}</td>
                                <td>{{ $student['name'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center py-4">No se encontraron estudiantes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-4">
                <h3 class="text-lg font-bold mb-4">Profesores disponibles en el Backend DDD</h3>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers['data'] ?? [] as $teacher)
                            <tr>
                                <td>{{ $teacher['id'] ?? 'N/A' }}</td>
                                <td>{{ $teacher['name'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center py-4">No se encontraron profesores.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-4">
                <h3 class="text-lg font-bold mb-4">Asignaturas disponibles en el Backend DDD</h3>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects['data'] ?? [] as $subject)
                            <tr>
                                <td>{{ $subject['id'] ?? 'N/A' }}</td>
                                <td>{{ $subject['name'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" class="text-center py-4">No se encontraron asignaturas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>