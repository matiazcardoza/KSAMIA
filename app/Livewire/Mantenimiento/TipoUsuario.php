<?php

namespace App\Livewire\Mantenimiento;

use App\Models\Tipo_usuario;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class TipoUsuario extends Component
{
    public $tipo_usuario, $id_tipo_usuario;

    public function mount(){
        $this->tipo_usuario=Tipo_usuario::all();
    }

    public function render()
    {
        return view('livewire.mantenimiento.tipo-usuario');
    }
    #[On("reloadTipo_usuario")]
    public function reloadTipo_usuario(){
        $this->tipo_usuario=Tipo_usuario::all();
    }

    public function editar($id_tipo_usuario){
        //dd($id_tipo_venta);
        $this->dispatch("EditarTipo_usuario", $id_tipo_usuario);
    }

    public function eliminar($id_tipo_usuario){
        $this->id_tipo_usuario=$id_tipo_usuario;
        Flux::modal("eliminar-tipo-usuario")->show();
    }

    public function destroy(){
        Tipo_usuario::find($this->id_tipo_usuario)->delete();
        $this->reloadTipo_usuario();
        Flux::modal("eliminar-tipo-usuario")->close();
    }
}
