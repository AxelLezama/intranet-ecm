<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentVersion;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Support\Facades\DB;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DocumentVersionService
{
    /*
        Función para crear la version inicial de un documento nuevo
        LLamado por DocumentManager al guardar un nuevo documento
    */

    public function createInitialVersion(
        Document $document,
        TemporaryUploadedFile|UploadedFile $file, #Ayuda a livewire a manejar documentos en tiempo real aun cuando son nulos
        float $version,
        string $changeType,
        string $startDate,
        ?string $note = null
    ): DocumentVersion {

        #Le damos la indicacion de como queremos almacenar los documentos y sus versiones
        # $file es el documento que se guardará así Document1/1.2 y consecutivos
        $path = $this->storeFile($file, $document->id, $version);

        return DocumentVersion::create([
            'document_id' => $document->id,
            'version' => $version,
            'file_path' => $path,
            'note' => $note,
            'change_type' => $changeType,
            'start_date' => $startDate,
            'end_date' => null,
            'created_by' => auth()->id,
            'updated_by' => auth()->id,
        ]);
    }

    /*
        Subir una versión a un documento existente
        Cerrar la versión anterior y actualizar current_version en documents
        llamando DocumentVersionManegr
    */

    public function uploadNewVersion(
        Document $document,
        TemporaryUploadedFile|UploadedFile $file,
        float $newVersion,
        string $changeType,
        string $startDate,
        ?string $note = null,
    ): DocumentVersion {
        return DB::transaction(function () use ($document, $file, $newVersion, $changeType, $startDate, $note) {

            #Cerrar versión vigente automaticamente al crear una nueva modificando un registro nulo
            DocumentVersion::where('document_id', $document->id)
                ->whereNull('end_date')
                ->update([
                    'end_date' => now()->toDateString(), #Fecha fin al momento de crear una nueva versión
                    'updated_by' => auth()->id,
                ]);

            #Subimos la nueva versión
            $path = $this->storeFile($file, $document->id, $newVersion);


            #Creamos la nueva versión
            $version = DocumentVersion::create([
                'document_id' => $document->id,
                'version' => $newVersion,
                'file_path' => $path,
                'note' => $note,
                'changeType' => $changeType,
                'start_date' => $startDate,
                'end_date' => null,
                'created_by' => auth()->id,
                'updated_by' => auth()->id,
            ]);

            #Actualizamos la versión actual en documents
            $document->update([
                'current_version' => $newVersion,
                'updated_by' => auth()->id,
            ]);

            return $version;
        });
    }


    #Formato para creación de carpetas
    public function storeFile(
        TemporaryUploadedFile|UploadedFile $file,
        int $documentId,
        float $version,
    ): string {
        $versionLabel = number_format($version, 2, '.','');
        $originalName = $file->getClientOriginalName(); #Obtiene el nombre local del archivo de la computadora del usuario que subirá el documento
        $fileName = "v{$versionLabel}_{$originalName}";

        return $file->storeAs(
            "documents/{$documentId}",
            $fileName,
            'public'
        );
    }
}
