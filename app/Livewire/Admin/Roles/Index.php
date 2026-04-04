<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Role;
use Livewire\Component;

class Index extends Component
{
    //Estas son mis variables para el formulario de creación/edición de roles
    public $name = '';
    public $description = '';
    public $roleId = null;
    public $isEditing = false;
    //Es de tipo array porque así se almacena el listado completo de roles
    public $roles = [];
    public $isModalOpen = false;

    public function mount()
    {
        $this->roles = Role::all();
    }

    public function render()
    {
        return view('livewire.admin.roles.index')
        ->layout('layouts.app'); 
    }

    public function store()
    {
        //Validamos roles
        $this->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:255'],
        ]);
        //Creamos un rol
        Role::create([
            'name' => $this->name,
            'description' => $this->description
        ]);
        
        #Recargamos los roles
        $this->roles = Role::all();

        //Reseteamos las propuiedades
        #Esto hace lo mismmo que $this->reset
        // $this->name = '';
        // $this->description = '';

        $this->reset(['name', 'description']);
        $this->isModalOpen = false;
    }

    public function edit($id)
    {
        #Buscamos un registro
        $role = Role::findOrFail($id);
        #Lo cargamos en el formulario
        $this->roleId = $role->id;
        $this->name = $role->name;
        $this->description = $role->description;
        
        #Activamos modo edicion
        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        //Validamos
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255']
        ]);

        //Buscamos el registro al que editaremos
        $role = Role::findOrFail($this->roleId);
        //Actualizamos el registro
        $role->update([
            'name' => $this->name,
            'description' => $this->description
        ]);

        //Refrescamos la lista
        $this->roles = Role::all();

        //reseteamos las propiedades
        $this->reset(['name','description','roleId','isEditing']);
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        $this->roles = Role::all();
    }


    public function closeModal()
    {
        $this->reset(['name', 'description', 'roleId', 'isEditing']);
        $this->isModalOpen = false;
    }
}
