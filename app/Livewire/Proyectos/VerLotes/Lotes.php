<?php

namespace App\Livewire\Proyectos\VerLotes;

use App\Models\Lote;
use App\Models\Manzana;
use App\Models\Proyecto;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class Lotes extends Component
{
    public $id_proyecto, $nombre, $ubicacion, $descripcion, $presupuesto, $fecha, $manzanas=[];
    public function render()
    {
        return view('livewire.proyectos.ver-lotes.lotes');
    }
    public function mount($id_proyecto=null){
        $this->reset('manzanas');
        $proyecto = Proyecto::find($id_proyecto);
        $this->id_proyecto=$id_proyecto;
        $this->nombre=$proyecto->nom_proyecto;
        $this->ubicacion=$proyecto->ubi_proyecto;
        $this->descripcion=$proyecto->descripcion_proyecto;
        $this->presupuesto=$proyecto->presupuesto_proyecto;
        $this->fecha=$proyecto->fecha_proyecto;

        $manzanas=Manzana::where('id_proyecto', $id_proyecto)->get();

        $this->manzanas = $manzanas->map(function ($manzana) {
            $lotes = Lote::where('id_manzana', $manzana->id_manzana)->get();

            return [
                'id_manzana' => $manzana->id_manzana,
                'nombreMz' => $manzana->nom_manzana,
                'descripcionMz' => $manzana->descr_manzana,
                'lotes' => $lotes->map(function ($lote) {
                    return [
                        'id_lote' => $lote->id_lote,
                        'numLote' => $lote->nom_lote,
                        'areaLote' => $lote->area_lote,
                        'precioLote' => $lote->precio_lote,
                        'estado' => $lote->est_lote
                    ];
                })->toArray()
            ];
        })->toArray();
    }

    public function separar($id_lote){
        $this->dispatch("SepararLote", $id_lote);
    }

    public function vender($id_lote, $id_proyecto){
        $this->dispatch("VenderLote", $id_lote, $id_proyecto);
    }

    #[On("reloadLotes")]
    function realoadLotes(){
        $manzanas = Manzana::where('id_proyecto', $this->id_proyecto)->get();
        $this->manzanas = $manzanas->map(function ($manzana) {
            $lotes = Lote::where('id_manzana', $manzana->id_manzana)->get();

            return [
                'id_manzana' => $manzana->id_manzana,
                'nombreMz' => $manzana->nom_manzana,
                'descripcionMz' => $manzana->descr_manzana,
                'lotes' => $lotes->map(function ($lote) {
                    return [
                        'id_lote' => $lote->id_lote,
                        'numLote' => $lote->nom_lote,
                        'areaLote' => $lote->area_lote,
                        'precioLote' => $lote->precio_lote,
                        'estado' => $lote->est_lote
                    ];
                })->toArray()
            ];
        })->toArray();
    }
}
