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
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>

                                </button>

                                <!-- Eliminar -->
                                <button
                                    class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                    onclick="confirm('¿Seguro que deseas eliminar este tipo de documento?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $docType->id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>

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
