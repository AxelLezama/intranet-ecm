<?php

namespace App\Livewire\Admin\Departments;

use App\Models\Department;
use Livewire\Component;

class Index extends Component
{

    public $name = '';
    public $is_active = true;
    public $departmentId = null;
    public $departments = [];
    public $isEditing = false;
    public $isModalOpen = false; 

    public function mount()
    {
        $this->departments = Department::all();
    }

    public function render()
    {
        return view('livewire.admin.departments.index')
        ->layout('layouts.app');
    }

    public function store()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255']
        ]);

        Department::create([
            'name' => $this->name,
            'is_active' => true
        ]);

        $this->departments = Department::all();

        $this->isModalOpen = false;
    }
}
