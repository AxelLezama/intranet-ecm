<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    // ── Helpers privados ──────────────────────────────────

    private function isAdmin(User $user): bool
    {
        return $user->role->name === 'admin';
    }

    private function isSupervisor(User $user): bool
    {
        return $user->role->name === 'supervisor';
    }

    private function isCommittee(User $user): bool
    {
        return $user->role->name === 'committee';
    }

    private function isEmployee(User $user): bool
    {
        return $user->role->name === 'employee';
    }

    // ── Acceso global para admin ───────────────────────────

    public function before(User $user): ?bool
    {
        if ($this->isAdmin($user)) return true;
        return null; // deja pasar al método específico
    }

    // ── Policies ──────────────────────────────────────────

    /**
     * Vista pública del empleado (cards).
     * Solo documentos activos de empleados.
     */
    public function viewPublic(User $user, Document $document): bool
    {
        return $document->status === 'active'
            && $document->visibility === 'employees';
    }

    /**
     * Vista de gestión (tabla).
     * Supervisor ve employees, comité ve committee.
     */
    public function viewManagement(User $user, Document $document): bool
    {
        if ($this->isSupervisor($user)) {
            return $document->visibility === 'employees';
        }

        if ($this->isCommittee($user)) {
            return $document->visibility === 'committee';
        }

        return false;
    }

    /**
     * ¿Puede crear documentos?
     */
    public function create(User $user): bool
    {
        return $this->isSupervisor($user) || $this->isCommittee($user);
    }

    /**
     * ¿Puede editar este documento?
     */
    public function update(User $user, Document $document): bool
    {
        if ($this->isSupervisor($user)) {
            return $document->visibility === 'employees';
        }

        if ($this->isCommittee($user)) {
            return $document->visibility === 'committee';
        }

        return false;
    }

    /**
     * ¿Puede archivar (dar de baja)?
     */
    public function archive(User $user, Document $document): bool
    {
        return $this->update($user, $document);
    }

    /**
     * ¿Puede subir nueva versión?
     */
    public function uploadVersion(User $user, Document $document): bool
    {
        return $this->update($user, $document);
    }

    /**
     * ¿Puede ver el historial de versiones?
     */
    public function viewVersions(User $user, Document $document): bool
    {
        if ($this->isEmployee($user)) {
            return $document->visibility === 'employees'
                && $document->status === 'active';
        }

        return $this->viewManagement($user, $document);
    }
}