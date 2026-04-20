<div class="p-6">

    <!-- Modal -->
    <x-modal-l :show="$isModalOpen" :title="$isEditing ? 'Editar Usuario' : 'Crear Usuario'" closeAction="closeModal">

        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'store' }}">

            <!-- Número de Empleado -->
            <input type="text" wire:model="employee_number" placeholder="Número de Empleado" class="w-full border p-2 mb-2 rounded">

            @error('employee_number')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <!-- Nombre -->
            <input type="text" wire:model="name" placeholder="Nombre" class="w-full border p-2 mb-2 rounded">

            @error('name')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <!-- Email -->
            <input type="email" wire:model="email" placeholder="Email" class="w-full border p-2 mb-2 rounded">

            @error('email')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <!-- Password -->
            <input type="password" wire:model="password" placeholder="Contraseña" class="w-full border p-2 mb-2 rounded">

            @error('password')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <!-- Rol -->
            <select wire:model="role_id" class="w-full border p-2 mb-2 rounded">
                <option value="">Seleccionar Rol</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>

            @error('role_id')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <!-- Departamento -->
            <select wire:model="department_id" class="w-full border p-2 mb-2 rounded">
                <option value="">Seleccionar Departamento</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>

            @error('department_id')
                <span class="text-red-500 text-sm uppercase">{{ $message }}</span>
            @enderror

            <!-- Activo -->
            <label class="flex items-center">
                <input type="checkbox" wire:model="is_active" class="mr-2">
                Activo
            </label>

            @error('is_active')
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
    <x-board-l title="Usuarios">

        <!-- Botón -->
        <x-slot name="actions">
            <button class="bg-blue-500 text-white px-4 py-2 rounded" wire:click="$set('isModalOpen', true)">
                Agregar Usuario
            </button>
        </x-slot>

        <!-- Tabla -->
        <table class="min-w-full divide-y-2 divide-gray-800">
            <thead>
                <tr class="*:font-medium *:text-gray-900">
                    <th class="px-3 py-2">ID</th>
                    <th class="px-3 py-2">Número de Empleado</th>
                    <th class="px-3 py-2">Nombre</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Rol</th>
                    <th class="px-3 py-2">Departamento</th>
                    <th class="px-3 py-2">Activo</th>
                    <th class="px-3 py-2">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-3 py-2 text-center">
                            {{ $user->id }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            {{ $user->employee_number }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            {{ $user->name }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            {{ $user->email }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            {{ $user->role->name ?? 'N/A' }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            {{ $user->department->name ?? 'N/A' }}
                        </td>

                        <td class="px-3 py-2 text-center">
                            {{ $user->is_active ? 'Sí' : 'No' }}
                        </td>

                        <td class="px-3 py-2 text-center space-x-2">

                            <!-- Editar -->
                            <div class="flex justify-center gap-2">

                                <!-- Editar -->
                                <button
                                    class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition"
                                    wire:click="edit({{ $user->id }})">
                                    Editar
                                </button>

                                <!-- Eliminar -->
                                <button
                                    class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition"
                                    onclick="confirm('¿Seguro que deseas eliminar este usuario?') || event.stopImmediatePropagation()"
                                    wire:click="delete({{ $user->id }})">
                                    Eliminar
                                </button>

                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">
                            No hay usuarios
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-board-l>
</div>
