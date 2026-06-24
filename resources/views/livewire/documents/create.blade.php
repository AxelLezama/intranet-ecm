<div>
    {{-- Alerta de éxito --}}
    @if ($success)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 4000)"
            class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm"
        >
            ✅ Documento creado y publicado correctamente.
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">

        {{-- Tipo de documento --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Tipo de documento <span class="text-red-500">*</span>
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
                Título <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   wire:model="title"
                   placeholder="Ej. Manual de Seguridad e Higiene"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            @error('title')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Departamentos (TomSelect) --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Departamentos <span class="text-red-500">*</span>
            </label>
            <select
                id="department-select"
                multiple
                class="w-full"
                x-data
                x-init="
                    const ts = new TomSelect($el, {
                        plugins: ['remove_button'],
                        placeholder: 'Selecciona departamentos...',
                        onChange(values) {
                            $wire.set('department_ids', values.map(Number));
                        }
                    });
                    $wire.$watch('department_ids', values => {
                        ts.clear(true);
                        values.forEach(v => ts.addItem(v, true));
                    });
                "
            >
                @foreach ($this->departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
            @error('department_ids')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Archivo PDF --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Archivo PDF <span class="text-red-500">*</span>
            </label>
            <input type="file"
                   wire:model="file"
                   accept=".pdf"
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
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Fecha de vigencia <span class="text-red-500">*</span>
            </label>
            <input type="date"
                   wire:model="start_date"
                   class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500" />
            @error('start_date')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Nota de versión --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nota de versión
                <span class="text-gray-400 font-normal">(opcional)</span>
            </label>
            <textarea wire:model="note"
                      rows="3"
                      placeholder="Descripción breve de los cambios..."
                      class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </textarea>
            @error('note')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Botón --}}
        <div class="flex justify-end pt-2">
            <button type="submit"
                    wire:loading.attr="disabled"
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