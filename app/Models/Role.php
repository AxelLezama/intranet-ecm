<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Obtener los usuarios que tienen este rol.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
