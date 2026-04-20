<?php

namespace App\Livewire\Admin\Users;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Index extends Component
{

    # Propiedades de modelo
    public $role_id = null;
    public $department_id = null;
    public $employee_number = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $is_active = true;
    public $userId = null;

    #Pripiedad para listar
    public $users = [];
    public $roles = [];
    public $departments = [];

    #Propiedad para interactuar
    public $isEditing = false;
    public $isModalOpen = false;
    
    public function mount()
    {
        $this->loadDepartments();
        $this->loadRoles();
        $this->loadUsers();
    }

    public function render()
    {
        return view('livewire.admin.users.index')
        ->layout('layouts.app');
    }

    public function loadUsers(){
        $this->users = User::all();
    }

    public function loadRoles()
    {
        $this->roles = Role::all();
    }

    public function loadDepartments()
    {
        $this->departments = Department::all();
    }

    public function store()
    {
        #Validamos los campos que llegan desde el formulario en un arreglo asociativo
        $this->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'employee_number' => ['required', 'string', 'min:4', 'max:7'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string'],
            'is_active' => ['required']
        ]);

        #Creamos el usuario con eloquent y se 
        User::create([
            'role_id' => $this->role_id,
            'department_id' => $this->department_id,
            'employee_number' => $this->employee_number,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'is_active' => $this->is_active
        ]);

        #Recargamos la lista
        $this->loadUsers();

        $this->reset('role_id','department_id','employee_number', 'name', 'email', 'password','is_active');
        $this->isModalOpen = false;


    }

    public function edit($id)
    {
        #Buscamos un registro
        $user = User::findOrFail($id);
        
        $this->userId = $user->id;
        #Lo cargamos en el formulario
        $this->role_id = $user->role_id;
        $this->department_id = $user->department_id;
        $this->employee_number = $user->employee_number;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_active = (bool)$user->is_active;

        #Activamos modo edicion
        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        //Validamos
        $this->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'employee_number' => ['required', 'string', 'min:4', 'max:7'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'password' => ['nullable', 'string'],
            'is_active' => ['required']
        ]);

        //Buscamos el registro al que editaremos
        $user = User::findOrFail($this->userId);
        //Actualizamos el registro
        $updateData = [
            'role_id' => $this->role_id,
            'department_id' => $this->department_id,
            'employee_number' => $this->employee_number,
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active
        ];
        if (!empty($this->password)) {
            $updateData['password'] = Hash::make($this->password);
        }
        $user->update($updateData);

        //Refrescamos la lista
        $this->loadUsers();

        //reseteamos las propiedades
        $this->reset(['role_id', 'department_id', 'employee_number', 'name', 'email', 'password', 'is_active', 'userId', 'isEditing']);
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        $this->loadUsers();
    }

    public function closeModal(){
        $this->reset('role_id','department_id','employee_number', 'name', 'email', 'password','is_active', 'userId', 'isEditing');
        $this->resetValidation();
        $this->isModalOpen = false;
    }
}
