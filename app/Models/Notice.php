<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'created_by', 
        'updated_by', 
        'title', 
        'content', 
        'image_path', 
        'priority', 
        'is_active', 
        'published_at', 
        'expires_at'
    ];

    /**
     * Obtener el usuario que creó el aviso.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Obtener el usuario que actualizó el aviso.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

