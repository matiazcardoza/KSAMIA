<?php

namespace App\Livewire\Mantenimiento;

use App\Models\Tipo_venta;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class TipoVentaEditar extends Component
{
    public $tipo_venta, $id_tipo_venta, $nombre, $estado;
    public function render()
    {
        return view('livewire.mantenimiento.tipo-venta-editar');
    }
    #[On("EditarTipo_venta")]
    public function EditarTipo_venta($id_tipo_venta){
        $tipo_venta = Tipo_venta::find($id_tipo_venta);
        $this->id_tipo_venta=$id_tipo_venta;
        $this->nombre=$tipo_venta->nom_tipo_venta;
        $this->estado=($tipo_venta->est_tipo_venta == 1) ? true : false;
        Flux::modal("editar-tipo-venta")->show();
    }

    public function update(){
        $this->validate([
            "nombre"=>"required",
            "estado"=>"required"
        ]);

        $tipo_venta = Tipo_venta::find($this->id_tipo_venta);
        $tipo_venta->nom_tipo_venta=$this->nombre;
        $tipo_venta->est_tipo_venta=$this->estado;

        $tipo_venta->save();

        Flux::modal("editar-tipo-venta")->close();
        $this->dispatch("reloadTipo_venta");
    }
}
