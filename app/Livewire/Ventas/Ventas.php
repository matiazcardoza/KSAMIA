<?php

namespace App\Livewire\Ventas;

use App\Models\Proyecto;
use App\Models\Tipo_venta;
use App\Models\Venta;
use Livewire\Component;

class Ventas extends Component
{
    public $proyecto, $id_proyecto;
    public $ventas;
    public function mount($id_proyecto=null){

    $this->id_proyecto = $id_proyecto;
    $this->proyecto = Proyecto::where('id_proyecto', $this->id_proyecto)->get();
        $this->ventas = Venta::select([
            'ventas.*',
            'lote.nom_lote',
            'lote.area_lote',
            'lote.precio_lote',
            'lote.est_lote',
            'manzana.nom_manzana',
            'proyecto.nom_proyecto',
            'proyecto.ubi_proyecto'
        ])
        ->leftJoin('lote', 'ventas.id_lote', '=', 'lote.id_lote')
        ->leftJoin('manzana', 'lote.id_manzana', '=', 'manzana.id_manzana')
        ->leftJoin('proyecto', 'manzana.id_proyecto', '=', 'proyecto.id_proyecto')
        ->where('ventas.est_venta', 1)
        ->where('proyecto.id_proyecto', $this->id_proyecto)
        ->get();
    }
    public function render()
    {
        return view('livewire.ventas.ventas');
    }
}
