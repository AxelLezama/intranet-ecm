<div class="p-6">
    <div class="space-y-6">

        {{-- Filtros --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar documento..."
                    class="w-full pl-9 rounded-xl border-gray-300 text-sm
                          focus:ring-blue-500 focus:border-blue-500" />
            </div>
            <select wire:model.live="filterType"
                class="rounded-xl border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Todos los tipos</option>
                @foreach ($this->documentTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Cards --}}
        <div wire:loading.class="opacity-50 pointer-events-none transition-opacity"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @forelse ($this->documents as $document)
                <div
                    class="bg-white rounded-2xl border border-gray-200 p-5
                        hover:shadow-md hover:border-blue-200 transition group flex flex-col">

                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="p-2 bg-blue-50 rounded-lg group-hover:bg-blue-100 transition">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2
                                     h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119
                                     6.414V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-medium">
                            {{ $document->documentType->name ?? '—' }}
                        </span>
                    </div>

                    <h3 class="font-semibold text-gray-900 text-sm leading-snug mb-2 line-clamp-2 flex-1">
                        {{ $document->title }}
                    </h3>

                    <div class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                        <span class="font-mono bg-gray-50 px-2 py-0.5 rounded text-blue-600">
                            @if ($document->currentVersion)
                                v{{ number_format($document->currentVersion->version, 2) }}
                            @else
                                —
                            @endif
                        </span>
                        <span>{{ $document->updated_at->format('d/m/Y') }}</span>
                    </div>

                    <div>
                        <a href="{{ route('documents.download', $document->id) }}"
                            class="mt-3 flex items-center justify-center gap-2 w-full py-2
                            text-xs font-medium text-blue-600 bg-blue-50
                            hover:bg-blue-100 rounded-xl transition">
                            Ver
                        </a>

                        <button type="button" wire:click="preview({{ $document->id }})"
                            class="mt-3 w-full rounded-xl bg-gray-100 py-2 text-xs font-medium text-gray-700 hover:bg-gray-200">
                            Ver detalles
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-16 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                             a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    No hay documentos disponibles.
                </div>
            @endforelse
        </div>

        @if ($this->previewDocument)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl">
                    <div class="mb-4 flex items-start justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">
                            {{ $this->previewDocument->title }}
                        </h2>

                        <button type="button" wire:click="closePreview" class="text-gray-400 hover:text-gray-700">
                            Cerrar
                        </button>
                    </div>

                    <dl class="space-y-3 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500">Tipo</dt>
                            <dd>{{ $this->previewDocument->documentType->name ?? '—' }}</dd>
                        </div>

                        <div>
                            <dt class="font-medium text-gray-500">Departamentos</dt>
                            <dd>{{ $this->previewDocument->departments->pluck('name')->join(', ') ?: '—' }}</dd>
                        </div>

                        <div>
                            <dt class="font-medium text-gray-500">Versión actual</dt>
                            <dd>
                                {{ $this->previewDocument->currentVersion
                                    ? number_format($this->previewDocument->currentVersion->version, 2)
                                    : '—' }}
                            </dd>
                        </div>

                        @if ($this->previewDocument->currentVersion)
                            <div>
                                <dt class="font-medium text-gray-500">Vigente desde</dt>
                                <dd>{{ $this->previewDocument->currentVersion->start_date }}</dd>
                            </div>

                            <div>
                                <dt class="font-medium text-gray-500">Nota</dt>
                                <dd>{{ $this->previewDocument->currentVersion->note ?: '—' }}</dd>
                            </div>
                        @endif
                    </dl>

                    <button type="button" wire:click="closePreview"
                        class="mt-6 w-full rounded-xl bg-gray-100 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Cerrar
                    </button>
                </div>
            </div>
        @endif

        @if ($this->documents->hasPages())
            <div>{{ $this->documents->links() }}</div>
        @endif
    </div>
</div>
