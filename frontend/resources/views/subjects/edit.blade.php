<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar Materia
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Modificar Información de la Materia</h3>
                <form action="{{ route('subjects.update', $subject['id']) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $subject['name'] ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="teacher_id" class="block text-gray-700 text-sm font-bold mb-2">Profesor:</label>
                        <select id="teacher_id" name="teacher_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            <option value="">Seleccionar profesor</option>
                            @foreach($teachers['data'] ?? [] as $teacher)
                                <option value="{{ $teacher['id'] }}" {{ old('teacher_id', $subject['teacher_id'] ?? '') == $teacher['id'] ? 'selected' : '' }}>
                                    {{ $teacher['id'] }} - {{ $teacher['name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-start mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Actualizar Materia
                        </button>
                        <a href="{{ route('subjects.index') }}" class="ml-4 inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
