<div class="p-6">
    <!-- Colocamos el titulo dependiendo el modo $isEditing-->
    <x-modal-l :show="$isModalOpen" :title="$isEditing ? 'Editar Tipo de documento' : 'Crear Tipo de documento'" closeAction="closeModal">
        <!-- Ejecutamos el metodo dependiendo el modo $isEditing-->
        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
            <input type="text" wire:model="name" placeholder="Nombre" class="w-full border p-2 mb-2 rounded">
            @error('name')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <textarea wire:model="description" placeholder="Descripción" class="w-full border p-2 mb-4 rounded"></textarea>
            @error('description')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

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



    <x-board-l title="Tipos de documentos">
        <x-slot name="actions">
            <button class="bg-blue-500 text-white px-4 py-2 rounded" wire:click="$set('isModalOpen', true)">
                Agregar tipo de documento
            </button>
        </x-slot>

        <table class="min-w-full divide-y-2 divide-gray-800">
            <thead>
                <tr>
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Tipo</th>
                    <th class="px-3 py-2">Descripción</th>
                    <th class="px-3 py-2">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($documentTypes as $docType)
                    <tr>
                        <td class="px-3 py-2 text-center">{{ $docType->id }}</td>
                        <td class="px-3 py-2 text-center">{{ $docType->name }}</td>
                        <td class="px-3 py-2 text-center">{{ $docType->description }}</td>
                        <td class="px-3 py-2 text-center">
                            <div class="flex justify-center gap-2">

                                <!-- Editar -->
                                <button
                                    class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                    wire:click="edit({{ $docType->id }})">
                                    Editar
                                </button>

                                <!-- Eliminar -->
                                <button
                                    class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                    onclick="confirm('¿Seguro que deseas eliminar este tipo de documento?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $docType->id }})">
                                    Eliminar
                                </button>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            No hay tipos de documentos
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-board-l>


</div>
