<?php
namespace App\Policies;

use App\Models\User;

class UserPolicy {
    //Si eres admin, puedes hacer   TODO
    public function before(User $user): ?bool{
        return $user->role->name === 'admin' ? true : null;
    }

    //¿Puedes registrar un usuario? Solo el admin
    public function create(User $user): ?bool{
        return $user->role->name === 'admin' ? true : null;
    }
}

?>