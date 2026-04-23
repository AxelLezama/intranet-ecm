<?php

namespace App\Livewire\Supervisor\Notices;

use App\Models\Notice;
use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    #Propiedades del modelo
    public $created_by = null;
    public $updated_by = null;
    public $title = '';
    public $content = '';
    public $image_path = '';
    public $priority = '';
    public $is_active = true;
    public $published_at = '';
    public $expires_at = '';
    public $userId = null;

    #Propiedades de listado
    public $notices = [];
    public $users = [];

    #Propiedades de estado
    public $isEditing = false;
    public $isModalOpen = false;

    #Listar al cargar la pagina
    public function mount()
    {
        $this->loadUsers();
        $this->loadNotices();
    }

    public function render()
    {
        return view('livewire.supervisor.notices.index')
            ->layout('layouts.app');
    }

    #Buscar usuarios
    public function loadUsers()
    {
        $this->users = User::all();
    }

    #Buscar notices
    public function loadNotices()
    {
        $this->notices = Notice::all();
    }

    public function store()
    {
        #Validamos

        #Creamos el registro

        #Refrescamos noticias

        #Reseteamos propiedades

        #Cerrar modal
        
    }

    public function closeModal()
    {
        $this->reset('created_by', 'updated_by', 'title', 'content', 'image_path', 'priority', 'is_active', 'published_at', 'expires_at');
        $this->resetValidation();
        $this->isModalOpen = false;
    }
}
