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

    public function updatingSearch(): void     { $this->resetPage(); }
    public function updatingFilterType(): void { $this->resetPage(); }

    public function getDocumentTypesProperty()
    {
        return DocumentType::orderBy('name')->get();
    }

    public function getDocumentsProperty()
    {
        return Document::query()
            ->with(['documentType', 'currentVersion'])
            ->where('status', 'active')
            ->where('visibility', 'employees')
            ->when($this->search, fn($q) =>
                $q->where('title', 'like', '%' . $this->search . '%')
            )
            ->when($this->filterType, fn($q) =>
                $q->where('document_type_id', $this->filterType)
            )
            ->orderByDesc('updated_at')
            ->paginate(12);
    }

    public function render()
    {
        return view('livewire.documents.public-index')
            ->layout('layouts.app');
    }
}