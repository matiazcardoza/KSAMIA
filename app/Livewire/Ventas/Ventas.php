<?php

namespace App\Livewire\Ventas;

use App\Models\Tipo_venta;
use App\Models\Venta;
use Livewire\Component;

class Ventas extends Component
{
    public $tipo_venta, $id_tipo_venta;
    public $ventas;
    public function mount($id_tipo_venta=null){

        $this->id_tipo_venta = $id_tipo_venta;
        $this->tipo_venta=Tipo_venta::where('id_tipo_venta', $this->id_tipo_venta)->get();
        $this->ventas = Venta::select([
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
        ->where('ventas.est_venta', 1)
        ->get();
    }
    public function render()
    {
        return view('livewire.ventas.ventas');
    }
}
