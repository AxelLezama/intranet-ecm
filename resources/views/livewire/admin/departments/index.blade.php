<div class="p-6">

    <!-- Modal -->
    <x-modal-l 
        :show="$isModalOpen"
        :title="$isEditing ? 'Editar Departamento' : 'Crear Departamento'"
        closeAction="closeModal"
    >

        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">

            <!-- Nombre -->
            <input 
                type="text" 
                wire:model="name" 
                placeholder="Nombre del departamento" 
                class="w-full border p-2 mb-2 rounded"
            > 

            @error('name')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <!-- Activo -->
            <div class="flex items-center gap-2 mb-4">
                <input 
                    id="active"
                    type="checkbox" 
                    wire:model="is_active"
                    class="rounded"
                >
                <label for="active">Activo</label>
            </div>

            <!-- Acciones -->
            <div class="flex justify-end gap-2">
                <button 
                    type="button" 
                    wire:click="closeModal"
                    class="px-4 py-2 border rounded"
                >
                    Cancelar
                </button>

                <button 
                    type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded"
                >
                    Guardar
                </button>
            </div>

        </form>

    </x-modal-l>

    <!-- Board -->
    <x-board-l title="Departamentos">

        <!-- Botón -->
        <x-slot name="actions">
            <button 
                class="bg-blue-500 text-white px-4 py-2 rounded"
                wire:click="$set('isModalOpen', true)"
            >
                Agregar Departamento
            </button>
        </x-slot>

        <!-- Tabla -->
        <table class="min-w-full divide-y-2 divide-gray-800">
            <thead>
                <tr class="*:font-medium *:text-gray-900">
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Nombre</th>
                    <th class="px-3 py-2">Estado</th>
                    <th class="px-3 py-2">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($departments as $department)
                    <tr>
                        <td class="px-3 py-2 text-center">
                            {{ $department->id }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            {{ $department->name }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            @if($department->is_active)
                                <span class="text-green-600 font-semibold">Activo</span>
                            @else
                                <span class="text-red-600 font-semibold">Inactivo</span>
                            @endif
                        </td>

                        <td class="px-3 py-2 text-center">

                            <div class="flex justify-center gap-2">

                                <!-- Editar -->
                                <button 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                    wire:click="edit({{ $department->id }})"
                                >
                                    Editar
                                </button>

                                <!-- Eliminar -->
                                <button 
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                    onclick="confirm('¿Seguro que deseas eliminar este departamento?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $department->id }})"
                                >
                                    Eliminar
                                </button>

                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            No hay departamentos
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </x-board-l>

</div>