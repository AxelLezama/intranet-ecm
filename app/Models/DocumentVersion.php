<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id', 
        'version', 
        'file_path', 
        'note', 
        'change_type', 
        'start_date', 
        'end_date', 
        'created_by', 
        'updated_by'
    ];

    /**
     * Obtener el documento al que pertenece esta versión.
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Obtener el usuario que creó esta versión.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Obtener el usuario que actualizó esta versión.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
