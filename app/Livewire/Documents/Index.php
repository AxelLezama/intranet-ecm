<?php

namespace App\Livewire\Documents;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentType;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // ── Filtros ───────────────────────────────────────────
    public string $search      = '';
    public string $filterType  = '';
    public string $filterDept  = '';

    // ── Reset paginación al filtrar ───────────────────────
    public function updatingSearch(): void     { $this->resetPage(); }
    public function updatingFilterType(): void { $this->resetPage(); }
    public function updatingFilterDept(): void { $this->resetPage(); }

    // ── Catálogos ─────────────────────────────────────────
    public function getDocumentTypesProperty()
    {
        return DocumentType::orderBy('name')->get();
    }

    public function getDepartmentsProperty()
    {
        return Department::where('is_active', true)->orderBy('name')->get();
    }

    // ── Query principal ───────────────────────────────────
    public function getDocumentsProperty()
    {
        $user = auth()->user();

        $visibility = match ($user->role->name) {
            'supervisor' => 'employees',
            'committee'  => 'committee',
            default      => null, // admin ve todo
        };

        return Document::query()
            ->with(['documentType', 'currentVersion', 'creator', 'departments'])
            ->when($visibility, fn($q) =>
                $q->where('visibility', $visibility)
            )
            ->when($this->search, fn($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterType, fn($q) =>
                $q->where('document_type_id', $this->filterType)
            )
            ->when($this->filterDept, fn($q) =>
                $q->whereHas('departments', fn($q2) =>
                    $q2->where('departments.id', $this->filterDept)
                )
            )
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    // ── Acciones ──────────────────────────────────────────
    public function archive(int $documentId): void
    {
        $document = Document::findOrFail($documentId);
        $this->authorize('archive', $document);
        $document->update(['status' => 'archived']);
        $this->dispatch('notify', message: 'Documento archivado correctamente.');
    }

    public function restore(int $documentId): void
    {
        $document = Document::findOrFail($documentId);
        $this->authorize('archive', $document);
        $document->update(['status' => 'active']);
        $this->dispatch('notify', message: 'Documento reactivado correctamente.');
    }

    public function render()
    {
        return view('livewire.documents.index')
            ->layout('layouts.app');
    }
}