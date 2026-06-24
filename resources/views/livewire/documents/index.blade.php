<div class="p-6">
    <div class="space-y-4">

        {{-- Notificación flash --}}
        @if (session('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
                class="rounded-lg bg-green-50 border border-green-200 p-4 text-green-800 text-sm">
                {{ session('message') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-gray-900">Gestión de documentos</h1>
            @can('create', \App\Models\Document::class)
                <a href="{{ route('documents.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white
                      text-sm font-medium rounded-lg hover:bg-blue-700 transition">
                    + Nuevo documento
                </a>
            @endcan
        </div>

        {{-- Filtros --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                        </svg>
                    </span>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por título..."
                        class="w-full pl-9 rounded-lg border-gray-300 text-sm
                              focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <select wire:model.live="filterType"
                    class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los tipos</option>
                    @foreach ($this->documentTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="filterDept"
                    class="rounded-lg border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los departamentos</option>
                    @foreach ($this->departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Tabla --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                            Título</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">Tipo
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                            Versión</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                            Departamentos</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                            Creado por</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                            Fecha</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($this->documents as $document)
                        <tr class="hover:bg-gray-50 transition">

                            {{-- Título + status --}}
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $document->title }}</div>
                                <span @class([
                                    'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium mt-1',
                                    'bg-green-100 text-green-700' => $document->status === 'active',
                                    'bg-gray-100 text-gray-500' => $document->status === 'archived',
                                ])>
                                    {{ $document->status === 'active' ? 'Activo' : 'Archivado' }}
                                </span>
                            </td>

                            {{-- Tipo --}}
                            <td class="px-4 py-3 text-gray-600">
                                {{ $document->documentType->name ?? '—' }}
                            </td>

                            {{-- Versión --}}
                            <td class="px-4 py-3">
                                @if ($document->currentVersion)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-mono">
                                        v{{ number_format($document->currentVersion->version, 2) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            {{-- Departamentos --}}
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($document->departments->take(2) as $dept)
                                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full text-xs">
                                            {{ $dept->name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-400 text-xs">Sin departamento</span>
                                    @endforelse
                                    @if ($document->departments->count() > 2)
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 rounded-full text-xs">
                                            +{{ $document->departments->count() - 2 }} más
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- Creado por --}}
                            <td class="px-4 py-3 text-gray-600">{{ $document->creator->name ?? '—' }}</td>

                            {{-- Fecha --}}
                            <td class="px-4 py-3 text-gray-500 whitespace-nowrap">
                                {{ $document->created_at->format('d/m/Y') }}
                            </td>

                            {{-- Acciones --}}
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Ver versiones --}}
                                    <a href="{{ route('documents.versions', $document->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium
                                          text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                        Versiones
                                    </a>

                                    {{-- Archivar / Reactivar --}}
                                    @can('archive', $document)
                                        @if ($document->status === 'active')
                                            <button wire:click="archive({{ $document->id }})"
                                                wire:confirm="¿Archivar este documento? Dejará de ser visible para los empleados."
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium
                                                   text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                                Archivar
                                            </button>
                                        @else
                                            <button wire:click="restore({{ $document->id }})"
                                                wire:confirm="¿Reactivar este documento?"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium
                                                   text-green-600 bg-green-50 hover:bg-green-100 rounded-lg transition">
                                                Reactivar
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586
                                         a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                No se encontraron documentos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($this->documents->hasPages())
                <div class="px-4 py-3 border-t border-gray-100">
                    {{ $this->documents->links() }}
                </div>
            @endif
        </div>
    </div>


</div>
