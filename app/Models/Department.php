<?php

namespace App\Models;

use Dom\Document;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
        'name',
        'is_active'
    ];

    /**
     * Obtener los usuarios que pertenecen a este departamento.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Obtener los documentos pertenecientes a cierto depto
     */

    public function documents()
    {
        return $this->belongsToMany(Document::class);
    }

}
