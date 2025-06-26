<?php

namespace App\Livewire\Mantenimiento;

use App\Models\Tipo_usuario;
use Flux\Flux;
use Livewire\Component;

class TipoUsuarioNuevo extends Component
{
    public $nombre, $estado=true;
    public function render()
    {
        return view('livewire.mantenimiento.tipo-usuario-nuevo');
    }
    
    public function submit(){
        $this->validate([
            "nombre"=>"required",
            "estado"=>"required"
        ]);

        Tipo_usuario::create([
            "nom_tipo_usuario"=>$this->nombre,
            "est_tipo_usuario"=>$this->estado ? 1 : 0
        ]);

        $this->resetForm();
        
        Flux::modal("nuevo-tipo-usuario")->close();
        $this->dispatch("reloadTipo_usuario");
    }

    public function resetForm(){
        $this->nombre="";
    }
}
