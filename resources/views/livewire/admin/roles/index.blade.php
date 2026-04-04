<div class="p-6">
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <!-- Modal -->
    @if ($isModalOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded shadow-lg w-96">

                <h2 class="text-lg font-bold mb-4">
                    {{ $isEditing ? 'Editar Rol' : 'Crear Rol' }}
                </h2>

                <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">

                    <input type="text" wire:model="name" placeholder="Nombre" class="w-full border p-2 mb-2">

                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    <textarea wire:model="description" placeholder="Descripción" class="w-full border p-2 mb-2"></textarea>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="closeModal">
                            Cancelar
                        </button>

                        <button class="bg-green-500 text-white px-4 py-2 rounded">
                            Guardar
                        </button>
                    </div>

                </form>

            </div>
        </div>
    @endif

    <div class="overflow-x-auto rounded border border-gray-300 shadow-sm">
        <button class="bg-blue-500 text-white p-4 rounded mb-2" wire:click="$set('isModalOpen', true)">
            Agregar Rol
        </button>
        <h1 class="text-center font-bold text-3xl">Roles</h1>
        <table class="min-w-full divide-y-2 divide-gray-200 rounded">
            <thead class="ltr:text-left rtl:text-right">
                <tr class="*:font-medium *:text-gray-900">
                    <th class="px-3 py-2 whitespace-nowrap">ID</th>
                    <th class="px-3 py-2 whitespace-nowrap">Rol</th>
                    <th class="px-3 py-2 whitespace-nowrap">Descripción</th>
                    <th class="px-3 py-2 whitespace-nowrap">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($roles as $role)
                    <tr class="*:text-gray-900 ">
                        <td class="px-3 py-2 whitespace-nowrap text-center">{{ $role->id }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-center">{{ $role->name }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-center">{{ $role->description }}</td>
                        <td class="px-3 py-2 whitespace-nowrap text-center">
                            <button class="text-blue-500" wire:click="edit({{ $role->id }})">
                                Editar
                            </button>
                            <button class="text-red-500"
                                onclick="confirm('¿Seguro que deseas eliminar este rol?') || event.stopImmediatePropagation()"
                                wire:click="delete({{ $role->id }})">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                @empty
                    <h1>No hay roles</h1>
                @endforelse


            </tbody>
        </table>
    </div>
</div>
