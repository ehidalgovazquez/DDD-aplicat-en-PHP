<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detalles de la Materia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Información de la Materia</h3>

                <div class="mb-4">
                    <strong class="block text-gray-700">ID:</strong>
                    <span>{{ $subject['id'] ?? 'N/A' }}</span>
                </div>
                <div class="mb-4">
                    <strong class="block text-gray-700">Nombre:</strong>
                    <span>{{ $subject['name'] ?? 'N/A' }}</span>
                </div>
                <div class="mb-4">
                    <strong class="block text-gray-700">ID Teacher:</strong>
                    <span>{{ $subject['teacher_id'] ?? 'N/A' }}</span>
                </div>

                <div class="mt-6">
                    <a href="{{ route('subjects.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
