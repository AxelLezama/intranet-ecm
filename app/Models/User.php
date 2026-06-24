<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar en lote.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'department_id',
        'employee_number',
        'name',
        'email',
        'email_verified_at',
        'password',
        'is_active',
    ];

    /**
     * Los atributos que deben ocultarse durante la serialización.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Obtener el rol al que pertenece el usuario.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Obtener el departamento al que pertenece el usuario.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Obtener los documentos creados por el usuario.
     */
    public function createdDocuments()
    {
        return $this->hasMany(Document::class, 'created_by');
    }

    /**
     * Obtener las versiones de documentos creadas por el usuario.
     */
    public function createdDocumentVersions()
    {
        return $this->hasMany(DocumentVersion::class, 'created_by');
    }

    /**
     * Obtener los avisos creados por el usuario.
     */
    public function createdNotices()
    {
        return $this->hasMany(Notice::class, 'created_by');
    }

    public function hasRole(string $role): bool
    {
        return $this->role?->name === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role?->name, $roles);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
    public function isSupervisor(): bool
    {
        return $this->hasRole('supervisor');
    }
    public function isCommittee(): bool
    {
        return $this->hasRole('committee');
    }
    public function isEmployee(): bool
    {
        return $this->hasRole('employee');
    }
}
