<?php

namespace App\Livewire\Mantenimiento;

use App\Models\Tipo_venta;
use Flux\Flux;
use Livewire\Component;
use Livewire\Attributes\On;

class TipoVenta extends Component
{
    public $tipo_venta, $id_tipo_venta;

    public function mount(){
        $this->tipo_venta=Tipo_venta::all();
    }
    public function render()
    {
        return view('livewire.mantenimiento.tipo-venta');
    }

    #[On("reloadTipo_venta")]
    public function reloadTipo_venta(){
        $this->tipo_venta=Tipo_venta::all();
    }

    public function editar($id_tipo_venta){
        //dd($id_tipo_venta);
        $this->dispatch("EditarTipo_venta", $id_tipo_venta);
    }

    public function eliminar($id_tipo_venta){
        $this->id_tipo_venta=$id_tipo_venta;
        Flux::modal("eliminar-tipo-venta")->show();
    }

    public function destroy(){
        Tipo_venta::find($this->id_tipo_venta)->delete();
        $this->reloadTipo_venta();
        Flux::modal("eliminar-tipo-venta")->close();
        $this->dispatch("reloadMenuVentas");
    }
}
