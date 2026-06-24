<?php

namespace App\Livewire\Documents;

use App\Models\Department;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\DocumentVersion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    // ── Form fields ──────────────────────────────────────

    public int $document_type_id;
    public string $title            = '';
    public array  $department_ids   = [];
    public ?string $note            = null;
    public string $change_type      = 'content';
    public string $start_date       = '';
    public $file; // TemporaryUploadedFile

    // ── UI state ─────────────────────────────────────────

    public bool $success = false;

    // ── Catálogos ─────────────────────────────────────────

    public function mount(): void
    {
        // Bloquear si no tiene permiso de crear
        $this->authorize('create', Document::class);

        $this->start_date = now()->format('Y-m-d');
    }

    // ── Computed para las vistas ──────────────────────────

    public function getDocumentTypesProperty()
    {
        return DocumentType::orderBy('name')->get();
    }

    public function getDepartmentsProperty()
    {
        return Department::where('is_active', true)->orderBy('name')->get();
    }

    // ── Submit ────────────────────────────────────────────

    public function save(): void
    {

        $this->authorize('create', Document::class);

        $this->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'title'            => 'required|string|max:255',
            'department_ids'   => 'required|array|min:1',
            'department_ids.*' => 'exists:departments,id',
            'file'             => 'required|file|mimes:pdf|max:10240',
            'note'             => 'nullable|string|max:1000',
            'change_type'      => 'required|in:content,validity',
            'start_date'       => 'required|date',
        ]);

        DB::transaction(function () {
            $path = $this->file->store('documents', 'local');

            $document = Document::create([
                'document_type_id' => $this->document_type_id,
                'title'            => $this->title,
                'status'           => 'active',
                'visibility'       => $this->resolveVisibility(), // ← automático
                'created_by'       => auth()->id(),
                'updated_by'       => auth()->id(),
            ]);

            $version = DocumentVersion::create([
                'document_id' => $document->id,
                'version'     => 1.00,
                'file_path'   => $path,
                'note'        => $this->note,
                'change_type' => $this->change_type,
                'start_date'  => $this->start_date,
                'created_by'  => auth()->id(),
                'updated_by'  => auth()->id(),
            ]);

            $document->update(['current_version_id' => $version->id]);
            $document->departments()->sync($this->department_ids);
        });

        $this->success = true;
        $this->reset(['title', 'department_ids', 'note', 'file', 'change_type']);
        $this->start_date = now()->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.documents.create')
            ->layout('layouts.app');
    }

    private function resolveVisibility(): string
    {
        return auth()->user()->role->name === 'committee'
            ? 'committee'
            : 'employees';
    }
}
