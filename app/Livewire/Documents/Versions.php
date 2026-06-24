<?php

namespace App\Livewire\Documents;

use App\Models\Document;
use App\Models\DocumentVersion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Versions extends Component
{
    use WithFileUploads;

    // ── Documento padre ───────────────────────────────────
    public Document $document;

    // ── Form nueva versión ────────────────────────────────
    public bool    $showForm    = false;
    public $file                = null;
    public string  $note        = '';
    public string  $change_type = 'content';
    public string  $start_date  = '';

    // ── UI state ──────────────────────────────────────────
    public bool $success = false;

    public function mount(Document $document): void
    {
        $this->authorize('viewVersions', $document);
        $this->document   = $document->load([
            'documentType',
            'currentVersion',
            'departments',
            'creator',
        ]);
        $this->start_date = now()->format('Y-m-d');
    }

    // ── Versiones ordenadas ───────────────────────────────
    public function getVersionsProperty()
    {
        return DocumentVersion::where('document_id', $this->document->id)
            ->with('creator')
            ->orderByDesc('version')
            ->get();
    }

    // ── Calcular siguiente número de versión ──────────────
    private function resolveNextVersion(): float
    {
        $last = DocumentVersion::where('document_id', $this->document->id)
            ->orderByDesc('version')
            ->value('version');

        if (! $last) return 1.00;

        return $this->change_type === 'content'
            ? floor($last) + 1          // 1.00 → 2.00, 2.05 → 3.00
            : round($last + 0.01, 2);   // 1.00 → 1.01, 1.09 → 1.10
    }

    // ── Subir nueva versión ───────────────────────────────
    public function saveVersion(): void
    {
        $this->authorize('uploadVersion', $this->document);

        $this->validate([
            'file'        => 'required|file|mimes:pdf|max:10240',
            'note'        => 'nullable|string|max:1000',
            'change_type' => 'required|in:content,validity',
            'start_date'  => 'required|date',
        ]);

        DB::transaction(function () {
            // Cerrar versión anterior
            DocumentVersion::where('document_id', $this->document->id)
                ->whereNull('end_date')
                ->update(['end_date' => now()]);

            // Guardar archivo
            $path = $this->file->store('documents', 'local');

            // Crear nueva versión
            $version = DocumentVersion::create([
                'document_id' => $this->document->id,
                'version'     => $this->resolveNextVersion(),
                'file_path'   => $path,
                'note'        => $this->note ?: null,
                'change_type' => $this->change_type,
                'start_date'  => $this->start_date,
                'created_by'  => auth()->id(),
                'updated_by'  => auth()->id(),
            ]);

            // Actualizar current_version_id
            $this->document->update([
                'current_version_id' => $version->id,
                'updated_by'         => auth()->id(),
            ]);

            // Refrescar modelo en memoria
            $this->document->refresh();
        });

        $this->success   = true;
        $this->showForm  = false;
        $this->reset(['file', 'note', 'change_type']);
        $this->start_date = now()->format('Y-m-d');
    }

    // ── Descarga segura ───────────────────────────────────
    public function download(int $versionId)
    {
        $version = DocumentVersion::where('document_id', $this->document->id)
            ->findOrFail($versionId);

        $this->authorize('viewVersions', $this->document);

        return Storage::disk('local')->download(
            $version->file_path,
            $this->document->title . '_v' . number_format($version->version, 2) . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.documents.versions')
            ->layout('layouts.app');
    }
}