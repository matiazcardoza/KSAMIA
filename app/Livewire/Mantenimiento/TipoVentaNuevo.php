<?php

namespace App\Livewire\Mantenimiento;

use App\Models\Tipo_venta;
use Flux\Flux;
use Livewire\Component;

class TipoVentaNuevo extends Component
{
    public $nombre, $estado=true;
    public function render()
    {
        return view('livewire.mantenimiento.tipo-venta-nuevo');
    }

    public function submit(){
        $this->validate([
            "nombre"=>"required",
            "estado"=>"required"
        ]);

        Tipo_venta::create([
            "nom_tipo_venta"=>$this->nombre,
            "est_tipo_venta"=>$this->estado ? 1 : 0
        ]);

        $this->resetForm();
        
        Flux::modal("nuevo-tipo-venta")->close();
        $this->dispatch("reloadTipo_venta");
        $this->dispatch("reloadMenuVentas");
    }

    public function resetForm(){
        $this->nombre="";
    }
}
