<?php

namespace App\Livewire\Admin\Users;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Index extends Component
{

    #Objetivo: Tener un formulario + tabla de usuarios que se controle desde PHP sin escribir JavaScript manual.

    #Propiedades del formulario (Modelo)
    public $role_id = null;
    public $department_id = null;
    public $employee_number = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $is_active = true;
    public $userId = null;

    #Propiedades de listado
    public $users = [];
    public $roles = [];
    public $departments = [];

    #Propiedades de estado
    public $isEditing = false;
    public $isModalOpen = false;

    #Objetivo: Cargar datos iniciales y decirle a Livewire qué vista renderizar.

    public function mount()
    {
        $this->loadRoles();
        $this->loadDepartments();
        $this->loadUsers();
    }

    public function loadRoles()
    {
        $this->roles = Role::all();
    }
    public function loadDepartments()
    {
        $this->departments = Department::all();
    }
    public function loadUsers()
    {
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.admin.users.index')
            ->layout('layouts.app');
    }

    #Objetivo: Tomar lo que el usuario escribió en el formulario → validarlo → guardarlo en BD → actualizar la vista

    public function store()
    {
        #Validar
        $this->validate([
            'employee_number' => ['required', 'string', 'unique:users,employee_number', 'min:4', 'max:7'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
            'is_active' => ['required'],
            'role_id' => ['exists:roles,id'],
            'department_id' => ['exists:departments,id']
        ]);
        #Crear usuario
        User::create([
            'employee_number' => $this->employee_number,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_active' => $this->is_active,
            'role_id' => $this->role_id,
            'department_id' => $this->department_id
        ]);
        #Recargar lista
        $this->loadUsers();
        #Limpiar formulario
        $this->reset(
            'employee_number',
            'name',
            'email',
            'password',
            'is_active',
            'role_id',
            'department_id'
        );
        #Cerrar modal
        $this->isModalOpen = false;
    }

    #Objetivo: Quiero editar un usuario existente sin romper validaciones ni lógica
    #edit() -> cargar datos al formulario
    #update() -> guardar cambios

    public function edit($id)
    {

        $user = User::findOrFail($id);

        #Para saber que usuario es el que editaremos guardamos el id
        $this->userId = $user->id;

        #Mandamos la información a los inputs
        $this->employee_number = $user->employee_number;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role_id = $user->role_id;
        $this->department_id = $user->department_id;
        $this->is_active = (bool)$user->is_active;

        #Activamos modo edicion y abrimos el modal
        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        #validamos
        $this->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'employee_number' => ['required', 'unique:users,employee_number,'.$this->userId, 'string' ,'min:4', 'max:7'], #Esto remplaza a rule para que ignore el campo de actualizacion del usuario a editar
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$this->userId],
            'password' => ['nullable', 'string'],
            'is_active' => ['required']
        ]);

        #Buscamos el registro que editaremos
            $registroUser = User::findOrFail($this->userId);
        #Actualizamos el registro
            $registroUser->update([
                'role_id' => $this->role_id,
                'department_id' => $this->department_id,
                'employee_number' => $this->employee_number,
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_active' => $this->is_active
            ]);
            $registroUser->save();
        #Refrescamos la lista
            $this->loadUsers();
        #Reseteamos las propiedades
            $this->reset('role_id', 'department_id', 'employee_number', 'name', 'email', 'password', 'is_active');
            $this->resetValidation();
            $this->isModalOpen = false;

    }

    #Eliminar un usuario
    public function delete($id)
    {
        $eliminarUser = User::findOrFail($id);
        $eliminarUser->delete();

        $this->loadUsers();
    }

    #Cerrar modal
    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
        $this->reset('role_id', 'department_id', 'employee_number', 'name', 'email', 'password', 'is_active');
    }
}
