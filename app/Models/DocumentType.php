<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Obtener los documentos de este tipo.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

}
