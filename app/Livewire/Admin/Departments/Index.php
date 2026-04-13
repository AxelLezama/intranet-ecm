<?php

namespace App\Livewire\Admin\Departments;

use App\Models\Department;
use Livewire\Component;

class Index extends Component
{
    // Props del formulario
    public $name = '';
    public $is_active = true;
    public $departmentId = null;

    // Listado
    public $departments = [];

    // Estados UI
    public $isEditing = false;
    public $isModalOpen = false;

    public function mount()
    {
        $this->loadDepartments();
    }

    public function render()
    {
        return view('livewire.admin.departments.index')
            ->layout('layouts.app');
    }

    //Cargar departamentos
    public function loadDepartments()
    {
        $this->departments = Department::all();
    }

    //Crear
    public function store()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255', 'unique:departments,name']
        ]);

        Department::create([
            'name' => $this->name,
            'is_active' => $this->is_active
        ]);

        $this->loadDepartments();
        $this->closeModal();
    }

    //Editar (llenar formulario)
    public function edit($id)
    {
        $department = Department::findOrFail($id);

        $this->departmentId = $department->id;
        $this->name = $department->name;
        $this->is_active = (bool)$department->is_active;

        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    //Actualizar
    public function update()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        $department = Department::findOrFail($this->departmentId);

        $department->update([
            'name' => $this->name,
            'is_active' => $this->is_active
        ]);

        $this->loadDepartments();
        $this->closeModal();
    }

    //Eliminar
    public function delete($id)
    {
        Department::findOrFail($id)->delete();

        $this->loadDepartments();
    }

    //Cerrar modal y resetear estado
    public function closeModal()
    {
        $this->reset(['name', 'is_active', 'departmentId', 'isEditing']);
        $this->resetValidation();
        $this->is_active = true; // valor por defecto
        $this->isModalOpen = false;
    }
}