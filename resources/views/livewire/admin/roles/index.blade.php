<div class="p-6">

    <!-- Modal -->
    <x-modal-l :show="$isModalOpen" :title="$isEditing ? 'Editar Rol' : 'Crear Rol'" closeAction="closeModal">
    <x-modal-l :show="$isModalOpen" title="Crear Documento" closeAction="closeModal">

        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">
        <form wire:submit.prevent="store">

            <!-- Nombre -->
            <input type="text" wire:model="name" placeholder="Nombre" class="w-full border p-2 mb-2 rounded">
            <!-- Título -->
            <input type="text" wire:model="title" placeholder="Título del documento" class="w-full border p-2 mb-2 rounded">
            @error('title')
                <span class="text-red-500 text-sm uppercase block mb-2">{{ $message }}</span>
            @enderror

            @error('name')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            <!-- Tipo de Documento -->
            <select wire:model="document_type_id" class="w-full border p-2 mb-2 rounded bg-white">
                <option value="">Seleccione un tipo de documento...</option>
                @foreach($documentTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name ?? 'Tipo ID: ' . $type->id }}</option>
                @endforeach
            </select>
            @error('document_type_id')
                <span class="text-red-500 text-sm uppercase block mb-2">{{ $message }}</span>
            @enderror

            <!-- Descripción -->
            <textarea wire:model="description" placeholder="Descripción" class="w-full border p-2 mb-4 rounded">
            </textarea>
            <!-- Archivo -->
            <input type="file" wire:model="file" class="w-full border p-2 mb-2 rounded bg-white">
            @error('file')
                <span class="text-red-500 text-sm uppercase block mb-2">{{ $message }}</span>
            @enderror

            @error('description')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            <!-- Grid para Versión y Fecha para ahorrar espacio vertical -->
            <div class="grid grid-cols-2 gap-4 mb-2">
                <!-- Versión -->
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Versión inicial:</label>
                    <input type="number" step="0.01" wire:model="version" placeholder="1.00" class="w-full border p-2 rounded">
                    @error('version')
                        <span class="text-red-500 text-sm uppercase block mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Fecha de Inicio -->
                <div>
                    <label class="text-sm text-gray-600 block mb-1">Fecha de inicio:</label>
                    <input type="date" wire:model="start_date" class="w-full border p-2 rounded">
                    @error('start_date')
                        <span class="text-red-500 text-sm uppercase block mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Notas -->
            <textarea wire:model="note" placeholder="Notas del cambio o versión (Opcional)" class="w-full border p-2 mb-4 rounded"></textarea>
            @error('note')
                <span class="text-red-500 text-sm uppercase block mb-2">{{ $message }}</span>
            @enderror

            <!-- Acciones -->
            <div class="flex justify-end gap-2">
                <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded">
                    Cancelar
                </button>

                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                    Guardar
                <!-- wire:loading.attr="disabled" evita que el usuario le de 2 clics mientras se sube el archivo -->
                <button type="submit" wire:loading.attr="disabled" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded transition disabled:opacity-50">
                    <span wire:loading.remove wire:target="store">Guardar</span>
                    <span wire:loading wire:target="store">Guardando...</span>
                </button>
            </div>

        </form>

    </x-modal-l>

    <!-- Board -->
    <x-board-l title="Roles">
    <x-board-l title="Documentos">

        <!-- Botón -->
        <x-slot name="actions">
            <button class="bg-blue-500 text-white px-4 py-2 rounded" wire:click="$set('isModalOpen', true)">
                Agregar Rol
            <button class="bg-blue-500 hover:bg-blue-600 transition text-white px-4 py-2 rounded" wire:click="$set('isModalOpen', true)">
                Agregar Documento
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
                    <th class="px-3 py-2 text-left">ID</th>
                    <th class="px-3 py-2 text-left">Título</th>
                    <th class="px-3 py-2 text-center">Tipo</th>
                    <th class="px-3 py-2 text-center">Versión Act.</th>
                    <th class="px-3 py-2 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($roles as $role)
                @forelse ($documents as $document)
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

                        <td class="px-3 py-2">{{ $document->id }}</td>
                        <td class="px-3 py-2 font-medium text-gray-900">{{ $document->title }}</td>
                        <td class="px-3 py-2 text-center">{{ $document->documentType->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-center">v.{{ $document->currentVersion->version ?? '1.00' }}</td>
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

                            <span class="text-sm text-gray-500 italic">Acciones pendientes</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            No hay roles
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            No hay documentos registrados en el sistema
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-board-l>
</div>
