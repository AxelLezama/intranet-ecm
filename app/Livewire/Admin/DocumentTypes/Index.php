<?php

namespace App\Livewire\Admin\DocumentTypes;

use App\Models\DocumentType;
use Livewire\Component;

class Index extends Component
{
    #Propiedades del modelo
    public $name = "";
    public $description = "";
    

    #Propiedad para obtener los registros
    public $documentTypes = [];

    #Estados 
    public $isEditing = false;
    public $isOpenModal = false;

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

    




    
}
