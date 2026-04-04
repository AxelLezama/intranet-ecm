<?php

namespace Illuminate\Contracts\View;

interface View extends \Illuminate\Contracts\Support\Renderable
{
    public function layout($viewName); //Solo lo usaremos en los componentes de livewire
}
