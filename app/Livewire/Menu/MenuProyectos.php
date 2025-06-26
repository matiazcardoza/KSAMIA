<?php

namespace App\Livewire\Menu;

use App\Models\Proyecto;
use App\Models\Tipo_venta;
use Livewire\Attributes\On;
use Livewire\Component;

class MenuProyectos extends Component
{
    public $menu_proyectos;

    public function mount(){
        $this->menu_proyectos=Proyecto::all();
    }

    #[On('reloadMenuVentas')]
    public function reloadMenu()
    {
        $this->menu_proyectos = Proyecto::all();  // Recargar los datos
    }

    public function render()
    {
        return view('livewire.menu.menu-proyectos');
    }
}
