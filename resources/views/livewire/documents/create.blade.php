<div class="p-6 bg-white mx-6 my-6 rounded-xl">


    {{-- Alerta de éxito --}}
    @if ($success)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm">
            Documento creado y publicado correctamente.
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">

        {{-- Tipo de documento --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tipo de documento
            </label>
            <select wire:model="document_type_id"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">— Selecciona un tipo —</option>
                @foreach ($this->documentTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            @error('document_type_id')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Título --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Título
            </label>
            <input type="text" wire:model="title" placeholder="Ej. Manual de Seguridad e Higiene"
                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            @error('title')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Departamentos (TomSelect) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Departamentos
            </label>

            <div wire:ignore x-data x-init="const ts = new TomSelect($refs.departments, {
                plugins: ['remove_button'],
                placeholder: 'Selecciona departamentos...',
                onChange(values) {
                    const ids = Array.isArray(values) ?
                        values.map(Number) :
                        (values ? [Number(values)] : []);
            
                    $wire.set('department_ids', ids);
                }
            });
            
            $wire.$watch('department_ids', values => {
                ts.clear(true);
                (values ?? []).forEach(id => ts.addItem(String(id), true));
            });">
                <select x-ref="departments" id="department-select" multiple class="w-full">
                    @foreach ($this->departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 flex gap-4">
                <button type="button" wire:click="selectAllDepartments"
                    class="mb-2 text-sm text-blue-600 hover:text-blue-800 p-2 bg-blue-100 rounded-sm mt-3">
                    Seleccionar todos los departamentos
                </button>
                <button type="button" wire:click="clearDepartments" class="text-sm text-gray-600 hover:text-gray-800">
                    Desmarcar todos
                </button>
            </div>

            @error('department_ids')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Archivo PDF --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Archivo PDF
            </label>
            <input type="file" wire:model="file" accept=".pdf"
                class="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-lg file:border-0
                          file:bg-blue-50 file:text-blue-700
                          hover:file:bg-blue-100" />
            <div wire:loading wire:target="file" class="mt-1 text-xs text-blue-500">
                Subiendo archivo...
            </div>
            @error('file')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Fecha de vigencia --}}
        <div>
            <p class="text-sm font-medium text-gray-700">Vigente desde hoy</p>
            <p class="mb-3 text-xs text-gray-500">
                El documento estará disponible desde la fecha en que lo crees.
            </p>

            <label class="mb-2 flex items-center gap-2 text-sm">
                <input type="checkbox" wire:model.live="never_expires">
                <span>No vence</span>
            </label>

            @if (!$never_expires)
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Fecha de vencimiento
                </label>

                <input type="date" wire:model="expires_at" min="{{ now()->addDay()->format('Y-m-d') }}"
                    class="w-full rounded-lg border-gray-300 shadow-sm">

                @error('expires_at')
                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            @endif
        </div>

        {{-- Nota de versión --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nota de versión
                <span class="text-gray-400 font-normal">(opcional)</span>
            </label>
            <textarea wire:model="note" rows="3" placeholder="Descripción breve de los cambios..."
                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </textarea>
            @error('note')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botón --}}
        <div class="flex justify-end pt-2">
            <button type="submit" wire:loading.attr="disabled"
                class="inline-flex items-center gap-2 px-6 py-2.5
                           bg-blue-600 text-white text-sm font-medium rounded-lg
                           hover:bg-blue-700 disabled:opacity-60 transition">
                <span wire:loading.remove wire:target="save">
                    Crear documento
                </span>
                <span wire:loading wire:target="save">
                    Guardando...
                </span>
            </button>
        </div>

    </form>
</div>
