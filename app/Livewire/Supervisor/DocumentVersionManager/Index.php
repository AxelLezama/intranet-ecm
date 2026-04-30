<?php

namespace App\Livewire\Supervisor\DocumentVersionManager;


use App\Models\DocumentVersion;
use Livewire\Component;

class Index extends Component
{

    #propiedades
    public $documents = []; 
    public $isModalOpen = false;
    public $isEditing = false;

    public function render()
    {
        return view('livewire.supervisor.document-version-manager.index')
        ->layout('layouts.app');
    }

    public function mount()
    {
        $this->loadDocuments();
    }

    public function store()
    {
        $this->isModalOpen = true;
    }

    public function loadDocuments ()
    {
        $this->documents = DocumentVersion::all();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
        $this->reset('documents');
    }
}
