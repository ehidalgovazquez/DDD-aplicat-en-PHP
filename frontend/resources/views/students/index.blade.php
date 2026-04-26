<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Estudiantes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- Mensajes de éxito o error --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">¡Error!</strong>
                        <span class="block sm:inline">{{ $errors->first() }}</span>
                    </div>
                @endif

                <h3 class="text-lg font-bold mb-4">Listado de Estudiantes</h3>
                <a href="{{ route('students.create') }}" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded mb-4 inline-block">Añadir Estudiante</a>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students['data'] ?? [] as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $student['id'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $student['name'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">{{ $student['email'] ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-500">
                                    <a href="{{ route('students.show', $student['id']) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                    <a href="{{ route('students.edit', $student['id']) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Editar</a>
                                    <form action="{{ route('students.destroy', $student['id']) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este estudiante?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4">No se encontraron estudiantes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
