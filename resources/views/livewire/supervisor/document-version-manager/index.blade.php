<div class="p-6">

    <!-- Modal -->
    <x-modal-l :show="$isModalOpen" :title="$isEditing ? 'Editar Rol' : 'Crear Rol'" closeAction="closeModal">

        <form>

            <!-- Versión -->
            <input type="number" placeholder="Nueva versión" class="w-full border p-2 mb-2 rounded">

            <span class="text-red-500 text-sm uppercase">

            </span>

            <!-- Tipo de cambio -->
            @if ($isEditing == true)
                <select class="w-full border p-2 mb-2 rounded">
                    <option>Contenido</option>
                    <option>Vigencia</option>
                </select>
            @endif

            <!-- Fecha inicio -->
            <input type="date" class="w-full border p-2 mb-2 rounded">

            <span class="text-red-500 text-sm uppercase">

            </span>

            <!-- Nota -->
            <textarea placeholder="Nota del cambio" class="w-full border p-2 mb-4 rounded"></textarea>

            <!-- Archivo -->
            <input type="file" class="w-full border p-2 mb-4 rounded">

            <span class="text-red-500 text-sm uppercase">

            </span>

            <!-- Acciones -->
            <div class="flex justify-end gap-2">
                <button type="button" class="px-4 py-2 border rounded" wire:click="$set('isModalOpen', false)">
                    Cancelar
                </button>

                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                    Subir versión
                </button>
            </div>

        </form>

    </x-modal-l>

    <!-- Board -->
    <x-board-l title="Historial de versiones">



        <!-- Tabla -->
        <table class="min-w-full divide-y-2 divide-gray-800">
            <thead>
                <tr class="*:font-medium *:text-gray-900">
                    <th class="px-3 py-2">Versión</th>
                    <th class="px-3 py-2">Tipo</th>
                    <th class="px-3 py-2">Nota</th>
                    <th class="px-3 py-2">Inicio</th>
                    <th class="px-3 py-2">Fin</th>
                    <th class="px-3 py-2">Usuario</th>
                    <th class="px-3 py-2">Archivo</th>
                    <th class="px-3 py-2">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="px-3 py-2 text-center">1.00</td>
                    <td class="px-3 py-2 text-center">Contenido</td>
                    <td class="px-3 py-2 text-center">Actualización inicial</td>
                    <td class="px-3 py-2 text-center">01/01/2026</td>
                    <td class="px-3 py-2 text-center">31/12/2026</td>
                    <td class="px-3 py-2 text-center">Admin</td>
                    <td class="px-3 py-2 text-center">
                        <a href="#" class="text-blue-500">Descargar</a>
                    </td>
                    <td class="px-3 py-2 text-center space-x-2">

                        <div class="flex justify-center gap-2">

                            <!-- Acciones -->
                            <button class="bg-blue-500 text-white px-4 py-2 rounded text-sm"
                                wire:click="$set('isModalOpen',true)">
                                Nueva versión
                            </button>

                            <!-- Eliminar -->
                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                Eliminar
                            </button>


                        </div>

                    </td>
                </tr>

                <tr>
                    <td colspan="8" class="text-center py-4 text-gray-500">
                        No hay versiones
                    </td>
                </tr>
            </tbody>
        </table>

    </x-board-l>
</div>
