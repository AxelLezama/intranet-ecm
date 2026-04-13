<?php

namespace App\Livewire\Admin\DocumentTypes;

use App\Models\DocumentType;
use Livewire\Component;

class Index extends Component
{
    #Propiedades del modelo
    public $name = "";
    public $description = "";
    public $documentTypeId = null;

    #Propiedad para obtener los registros
    public $documentTypes = [];

    #Estados 
    public $isEditing = false;
    public $isModalOpen = false;

    public function mount()
    {
        $this->loadDocumentTypes();
    }


    public function render()
    {
        return view('livewire.admin.document-types.index')
            ->layout('layouts.app');
    }

    public function loadDocumentTypes()
    {
        $this->documentTypes = DocumentType::all();
    }

    public function store()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255']
        ]);

        DocumentType::create([
            'name' => $this->name,
            'description' => $this->description
        ]);

        $this->documentTypes = DocumentType::all();

        $this->reset(['name', 'description']);
        $this->isModalOpen = false;
    }


    public function edit($id)
    {
        #Buscamos un registro
        $documentType = DocumentType::findOrFail($id);

        #Llenamos el formulario de edicion
        $this->documentTypeId = $documentType->id;
        $this->name = $documentType->name;
        $this->description = $documentType->description;

        #Activamos modo edición y abrimos el modal
        $this->isEditing = true;
        $this->isModalOpen = true;
    }

    public function update()
    {
        #validamos
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255']
        ]);

        $documentType = DocumentType::findOrFail($this->documentTypeId);

        $documentType->update([
            'name' => $this->name,
            'description' => $this->description
        ]);

        $this->documentTypes = DocumentType::all();

        $this->reset(['name', 'description', 'documentTypeId', 'isEditing']);
        $this->isModalOpen = false;
    }

    public function delete($id)
    {
        $documentType = DocumentType::findOrFail($id);
        $documentType->delete();

        $this->documentTypes = DocumentType::all();
    }

    public function closeModal()
    {
        $this->reset(['name', 'description', 'documentTypeId', 'isEditing']);
        $this->resetValidation();
        $this->isModalOpen = false;
    }
}
