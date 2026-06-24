<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'document_type_id',
        'title',
        'status',
        'visibility',        
        'current_version_id',
        'created_by',
        'updated_by',
    ];

    protected $cast = [
        'status' => 'string'
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
    /**
     * Obtener la versión actual/activa del documento.
     */
    public function currentVersion()
    {
        return $this->belongsTo(DocumentVersion::class, 'current_version_id');
    }
    /**
     * Obtener los departamentos a los que pertenece el documento.
     */
    public function departments()
    {
        return $this->belongsToMany(Department::class)
            ->withTimestamps();
    }

    public function latestVersion()
    {
        return $this->hasOne(DocumentVersion::class)->latestOfMany('version');
    }
}
