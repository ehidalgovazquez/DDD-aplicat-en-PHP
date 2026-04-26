<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalles del Profesor
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Información del Profesor</h3>

                <div class="mb-4">
                    <strong class="block text-gray-700">ID:</strong>
                    <span>{{ $teacher['id'] ?? 'N/A' }}</span>
                </div>
                <div class="mb-4">
                    <strong class="block text-gray-700">Nombre:</strong>
                    <span>{{ $teacher['name'] ?? 'N/A' }}</span>
                </div>
                <div class="mb-4">
                    <strong class="block text-gray-700">Email:</strong>
                    <span>{{ $teacher['email'] ?? 'N/A' }}</span>
                </div>

                <div class="mt-6">
                    <a href="{{ route('teachers.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
