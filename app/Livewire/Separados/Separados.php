<?php

namespace App\Livewire\Separados;

use App\Models\Venta;
use Livewire\Component;

class Separados extends Component
{
    public $separados;

    public function mount()
    {
        $this->separados = Venta::select([
            'ventas.*',
            'lote.nom_lote',
            'lote.area_lote',
            'lote.precio_lote',
            'manzana.nom_manzana',
            'proyecto.nom_proyecto',
            'proyecto.ubi_proyecto'
        ])
        ->leftJoin('lote', 'ventas.id_lote', '=', 'lote.id_lote')
        ->leftJoin('manzana', 'lote.id_manzana', '=', 'manzana.id_manzana')
        ->leftJoin('proyecto', 'manzana.id_proyecto', '=', 'proyecto.id_proyecto')
        ->where('ventas.est_venta', 2)
        ->get();
    }

    public function render()
    {
        return view('livewire.separados.separados');
    }
}
