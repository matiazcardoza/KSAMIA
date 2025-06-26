<?php

namespace App\Livewire\Mantenimiento;

use App\Models\Tipo_usuario;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class TipoUsuarioEditar extends Component
{
    public $tipo_usuario, $id_tipo_usuario, $nombre, $estado;
    public function render()
    {
        return view('livewire.mantenimiento.tipo-usuario-editar');
    }
    #[On("EditarTipo_usuario")]
    public function EditarTipo_usuario($id_tipo_usuario){
        $tipo_usuario = Tipo_usuario::find($id_tipo_usuario);
        $this->id_tipo_usuario=$id_tipo_usuario;
        $this->nombre=$tipo_usuario->nom_tipo_usuario;
        $this->estado=($tipo_usuario->est_tipo_usuario == 1) ? true : false;
        
        Flux::modal("editar-tipo-usuario")->show();
    }

    public function update(){
        $this->validate([
            "nombre"=>"required",
            "estado"=>"required"
        ]);

        $tipo_usuario = Tipo_usuario::find($this->id_tipo_usuario);
        $tipo_usuario->nom_tipo_usuario=$this->nombre;
        $tipo_usuario->est_tipo_usuario=$this->estado;

        $tipo_usuario->save();

        Flux::modal("editar-tipo-usuario")->close();
        $this->dispatch("reloadTipo_usuario");
    }
}
