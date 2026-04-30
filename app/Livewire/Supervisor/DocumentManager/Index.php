<?php

namespace App\Livewire\Supervisor\DocumentManager;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.supervisor.document-manager.index')
        ->layout('layouts.app');
    }
}
