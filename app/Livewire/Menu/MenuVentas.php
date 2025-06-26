<?php

namespace App\Livewire\Menu;

use App\Models\Tipo_venta;
use Livewire\Attributes\On;
use Livewire\Component;

class MenuVentas extends Component
{
    public $menu_ventas;

    public function mount(){
        $this->menu_ventas=Tipo_venta::all();
    }

    #[On('reloadMenuVentas')]
    public function reloadMenu()
    {
        $this->menu_ventas = Tipo_venta::all();  // Recargar los datos
    }

    public function render()
    {
        return view('livewire.menu.menu-ventas');
    }
}
