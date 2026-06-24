<div class="p-6">
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                    <a href="{{ route('documents.index') }}" class="hover:text-blue-600 transition">Documentos</a>
                    <span>/</span>
                    <span class="text-gray-700 font-medium">{{ $document->title }}</span>
                </div>
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-medium">
                        {{ $document->documentType->name ?? '—' }}
                    </span>
                    <span @class([
                        'px-2 py-0.5 rounded-full text-xs font-medium',
                        'bg-green-100 text-green-700' => $document->status === 'active',
                        'bg-gray-100 text-gray-500' => $document->status === 'archived',
                    ])>
                        {{ $document->status === 'active' ? 'Activo' : 'Archivado' }}
                    </span>
                    @if ($document->currentVersion)
                        <span class="font-mono text-xs bg-blue-50 text-blue-700 px-2 py-0.5 rounded">
                            Versión actual: v{{ number_format($document->currentVersion->version, 2) }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Botón nueva versión --}}
            @can('uploadVersion', $document)
                @if ($document->status === 'active')
                    <button wire:click="$toggle('showForm')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white
                               text-sm font-medium rounded-lg hover:bg-blue-700 transition whitespace-nowrap">
                        {{ $showForm ? 'Cancelar' : '+ Nueva versión' }}
                    </button>
                @endif
            @endcan
        </div>

        {{-- Alerta éxito --}}
        @if ($success)
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm">
                ✅ Nueva versión publicada correctamente.
            </div>
        @endif

        {{-- Formulario nueva versión --}}
        @if ($showForm)
            <div class="bg-white rounded-xl border border-blue-200 p-5 space-y-4">
                <h2 class="font-semibold text-gray-800 text-sm">Nueva versión</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Tipo de cambio --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tipo de cambio <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="change_type"
                            class="w-full rounded-lg border-gray-300 text-sm
                                   focus:ring-blue-500 focus:border-blue-500">
                            <option value="content">Contenido (versión mayor)</option>
                            <option value="validity">Vigencia (versión menor)</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-400">
                            @if ($change_type === 'content')
                                Siguiente versión:
                                v{{ number_format(floor($document->currentVersion?->version ?? 0) + 1, 2) }}
                            @else
                                Siguiente versión:
                                v{{ number_format(($document->currentVersion?->version ?? 0) + 0.01, 2) }}
                            @endif
                        </p>
                        @error('change_type')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Fecha de vigencia --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha de vigencia <span class="text-red-500">*</span>
                        </label>
                        <input type="date" wire:model="start_date"
                            class="w-full rounded-lg border-gray-300 text-sm
                                  focus:ring-blue-500 focus:border-blue-500" />
                        @error('start_date')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Archivo --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Archivo PDF <span class="text-red-500">*</span>
                        </label>
                        <input type="file" wire:model="file" accept=".pdf"
                            class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4 file:rounded-lg
                                  file:border-0 file:bg-blue-50 file:text-blue-700
                                  hover:file:bg-blue-100" />
                        <div wire:loading wire:target="file" class="mt-1 text-xs text-blue-500">Subiendo archivo...
                        </div>
                        @error('file')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nota --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nota de cambios
                            <span class="text-gray-400 font-normal">(opcional)</span>
                        </label>
                        <textarea wire:model="note" rows="2" placeholder="Describe brevemente los cambios realizados..."
                            class="w-full rounded-lg border-gray-300 text-sm
                                     focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('note')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button wire:click="saveVersion" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600
                               text-white text-sm font-medium rounded-lg
                               hover:bg-blue-700 disabled:opacity-60 transition">
                        <span wire:loading.remove wire:target="saveVersion">Publicar versión</span>
                        <span wire:loading wire:target="saveVersion">Guardando...</span>
                    </button>
                </div>
            </div>
        @endif

        {{-- Historial de versiones --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-700 text-sm">Historial de versiones</h2>
            </div>

            <ul class="divide-y divide-gray-100">
                @forelse ($this->versions as $version)
                    <li class="px-4 py-4 flex items-start justify-between gap-4 hover:bg-gray-50 transition">
                        <div class="flex items-start gap-3">
                            {{-- Badge versión --}}
                            <span @class([
                                'font-mono text-xs px-2 py-1 rounded-md mt-0.5 whitespace-nowrap',
                                'bg-blue-600 text-white' => $document->current_version_id === $version->id,
                                'bg-gray-100 text-gray-600' =>
                                    $document->current_version_id !== $version->id,
                            ])>
                                v{{ number_format($version->version, 2) }}
                            </span>

                            <div class="space-y-0.5">
                                {{-- Tipo de cambio --}}
                                <div class="flex items-center gap-2">
                                    <span @class([
                                        'text-xs px-1.5 py-0.5 rounded font-medium',
                                        'bg-orange-50 text-orange-600' => $version->change_type === 'content',
                                        'bg-teal-50 text-teal-600' => $version->change_type === 'validity',
                                    ])>
                                        {{ $version->change_type === 'content' ? 'Contenido' : 'Vigencia' }}
                                    </span>
                                    @if ($document->current_version_id === $version->id)
                                        <span class="text-xs text-blue-600 font-medium">● Actual</span>
                                    @endif
                                </div>

                                {{-- Nota --}}
                                @if ($version->note)
                                    <p class="text-sm text-gray-600">{{ $version->note }}</p>
                                @endif

                                {{-- Meta --}}
                                <p class="text-xs text-gray-400">
                                    Vigencia desde {{ \Carbon\Carbon::parse($version->start_date)->format('d/m/Y') }}
                                    @if ($version->end_date)
                                        hasta {{ \Carbon\Carbon::parse($version->end_date)->format('d/m/Y') }}
                                    @else
                                        · vigente
                                    @endif
                                    · subido por {{ $version->creator->name ?? 'Sistema' }}
                                </p>
                            </div>
                        </div>

                        {{-- Descarga --}}
                        @if ($version->file_path)
                            <button wire:click="download({{ $version->id }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium
                                       text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg transition
                                       whitespace-nowrap shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Descargar
                            </button>
                        @endif
                    </li>
                @empty
                    <li class="px-4 py-10 text-center text-gray-400 text-sm">
                        No hay versiones registradas.
                    </li>
                @endforelse
            </ul>
        </div>
    </div>


</div>
