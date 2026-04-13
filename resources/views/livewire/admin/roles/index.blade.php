<div class="p-6">

    <!-- Modal -->
    <x-modal-l :show="$isModalOpen" :title="$isEditing ? 'Editar Rol' : 'Crear Rol'" closeAction="closeModal">

        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">

            <!-- Nombre -->
            <input type="text" wire:model="name" placeholder="Nombre" class="w-full border p-2 mb-2 rounded">

            @error('name')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <!-- Descripción -->
            <textarea wire:model="description" placeholder="Descripción" class="w-full border p-2 mb-4 rounded">
            </textarea>

            @error('description')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <!-- Acciones -->
            <div class="flex justify-end gap-2">
                <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded">
                    Cancelar
                </button>

                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                    Guardar
                </button>
            </div>

        </form>

    </x-modal-l>

    <!-- Board -->
    <x-board-l title="Roles">

        <!-- Botón -->
        <x-slot name="actions">
            <button class="bg-blue-500 text-white px-4 py-2 rounded" wire:click="$set('isModalOpen', true)">
                Agregar Rol
            </button>
        </x-slot>

        <!-- Tabla -->
        <table class="min-w-full divide-y-2 divide-gray-800">
            <thead>
                <tr class="*:font-medium *:text-gray-900">
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Rol</th>
                    <th class="px-3 py-2">Descripción</th>
                    <th class="px-3 py-2">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($roles as $role)
                    <tr>
                        <td class="px-3 py-2 text-center">
                            {{ $role->id }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            {{ $role->name }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            {{ $role->description }}
                        </td>

                        <td class="px-3 py-2 text-center space-x-2">

                            <!-- Editar -->
                            <div class="flex justify-center gap-2">

                                <!-- Editar -->
                                <button
                                    class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                    wire:click="edit({{ $role->id }})">
                                    Editar
                                </button>

                                <!-- Eliminar -->
                                <button
                                    class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                    onclick="confirm('¿Seguro que deseas eliminar este rol?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $role->id }})">
                                    Eliminar
                                </button>

                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            No hay roles
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-board-l>
</div>
