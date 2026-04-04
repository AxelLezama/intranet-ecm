<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'document_type_id',
        'title',
        'current_version',
        'created_by',
        'updated_by'
    ];

    /**
     * Obtener el tipo de documento.
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Obtener el usuario que creó el documento.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Obtener el usuario que actualizó el documento.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Obtener las versiones del documento.
     */
    public function versions()
    {
        return $this->hasMany(DocumentVersion::class);
    }
}
