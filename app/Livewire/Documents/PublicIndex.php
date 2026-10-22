<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentType;
use Livewire\Component;
use Livewire\WithPagination;

class PublicIndex extends Component
{
    use WithPagination;

    public string $search      = '';
    public string $filterType  = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilterType(): void
    {
        $this->resetPage();
    }
    public ?int $previewDocumentId = null;

    public function getDocumentTypesProperty()
    {
        return DocumentType::orderBy('name')->get();
    }

    public function getDocumentsProperty()
    {
        return $this->visibleDocumentsQuery()
            ->when(
                $this->search,
                fn($q) => $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->when(
                $this->filterType,
                fn($q) => $q->where('document_type_id', $this->filterType)
            )
            ->orderByDesc('updated_at')
            ->paginate(12);
    }

    public function preview(int $documentId): void
    {
        $this->previewDocumentId = $documentId;

        // Comprueba que el empleado tenga acceso a este documento.
        $this->getPreviewDocumentProperty();
    }

    public function closePreview(): void
    {
        $this->previewDocumentId = null;
    }

    public function getPreviewDocumentProperty()
    {
        if (! $this->previewDocumentId) {
            return null;
        }

        return $this->visibleDocumentsQuery()
            ->findOrFail($this->previewDocumentId);
    }

    private function visibleDocumentsQuery()
    {
        $user = auth()->user();
        $role = $user->role?->name;

        $query = Document::query()
            ->with(['documentType', 'currentVersion', 'departments'])
            ->where('status', 'active');

        if ($role === 'admin') {
            return $query;
        }

        if ($role === 'supervisor') {
            return $query->where('visibility', 'employees');
        }

        if ($role === 'committee') {
            return $query->where('visibility', 'committee'); // Confirma que ese sea el valor guardado
        }

        if ($role === 'employee') {
            return $query
                ->where('visibility', 'employees')
                ->whereHas(
                    'departments',
                    fn($q) => $q->where('departments.id', $user->department_id)
                )
                ->whereHas(
                    'currentVersion',
                    fn($q) => $q->whereDate('start_date', '<=', today())
                        ->where(fn($dates) => $dates->whereNull('expires_at')
                            ->orWhereDate('expires_at', '>', today()))
                );
        }

        abort(403);
    }

    public function render()
    {
        return view('livewire.documents.public-index')
            ->layout('layouts.app');
    }
}
