<?php

namespace App\Livewire\Proyectos;

use App\Models\Lote;
use App\Models\Manzana;
use App\Models\Proyecto;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProyectoEditar extends Component
{
    use WithFileUploads;

    public $id_proyecto, $nombre, $ubicacion, $descripcion, $presupuesto, $manzanas=[], $fecha, $pdfPlano, $planoActual;

    public $descripcionMz, $areaLote, $precioLote;

    public function agregarmanzana(){
        $ultimaManzana = end($this->manzanas);
        if ($ultimaManzana) {
            $ultimoNombre = $ultimaManzana['nombreMz'];
            $nuevoNombre = $this->siguienteLetra($ultimoNombre);
        } else {
            $nuevoNombre = 'A';
        }

        $this->manzanas[] = [
            'nombreMz' => $nuevoNombre,
            'descripcionMz' => $this->descripcionMz,
            'estadoMz'=>1,
            'lotes' => []
        ];
        $this->descripcionMz = '';
    }

    private function siguienteLetra($letra) {
        if ($letra === 'Z') {
            return 'A';
        }
        return chr(ord($letra) + 1);
    }

    public function agregarLote($manzanaKey){
        $ultimoLote = end($this->manzanas[$manzanaKey]['lotes']);

        $nuevoNumeroLote = $ultimoLote 
        ? ($ultimoLote['numLote'] + 1) 
        : 1;

        $this->manzanas[$manzanaKey]['lotes'][]=[
            'numLote'=>$nuevoNumeroLote,
            'areaLote'=>$this->areaLote,
            'precioLote'=>$this->precioLote,
            'estLote'=>1
        ];

        $this->areaLote='';
        $this->precioLote='';
    }

    public function eliminarManzana($key)
    {
        if (isset($this->manzanas[$key]['id_manzana'])) {
            $manzanaId = $this->manzanas[$key]['id_manzana'];     
            Lote::where('id_manzana', $manzanaId)->delete();
            Manzana::where('id_manzana', $manzanaId)->delete();
        }

        unset($this->manzanas[$key]);
        $this->manzanas = array_values($this->manzanas);
    }

    public function eliminarLote($key, $loteKey)
    {
        if(isset($this->manzanas[$key]['lotes'][$loteKey]['id_lote'])){
            $loteId=$this->manzanas[$key]['lotes'][$loteKey]['id_lote'];
            Lote::where('id_lote', $loteId)->delete();
        }
        unset($this->manzanas[$key]['lotes'][$loteKey]);
        $this->manzanas[$key]['lotes'] = array_values($this->manzanas[$key]['lotes']);
    }

    public function render()
    {
        return view('livewire.proyectos.proyecto-editar');
    }

    #[On("EditarProyecto")]
    public function EditarProyecto($id_proyecto){
        $this->reset('manzanas');
        $proyecto = Proyecto::find($id_proyecto);
        $this->id_proyecto=$id_proyecto;
        $this->nombre=$proyecto->nom_proyecto;
        $this->ubicacion=$proyecto->ubi_proyecto;
        $this->descripcion=$proyecto->descripcion_proyecto;
        $this->presupuesto=$proyecto->presupuesto_proyecto;
        $this->fecha=$proyecto->fecha_proyecto;
        $this->planoActual = $proyecto->pdf_ruta_proyecto;

        $manzanas=Manzana::where('id_proyecto', $id_proyecto)->get();

        foreach ($manzanas as $manzana) {
            $lotes=Lote::where('id_manzana', $manzana->id_manzana)->get();

            $manzanaData=[
                'id_manzana'=>$manzana->id_manzana,
                'nombreMz'=>$manzana->nom_manzana,
                'descripcionMz'=>$manzana->descr_manzana,
                'lotes'=>$lotes->map(function($lote){
                    return[
                        'id_lote'=>$lote->id_lote,
                        'numLote'=>$lote->nom_lote,
                        'areaLote'=>$lote->area_lote,
                        'precioLote'=>$lote->precio_lote
                    ];
                })->toArray()
            ];

            $this->manzanas[]=$manzanaData;
        }

        Flux::modal("editar-proyecto")->show();
    }

    public function update(){
        $this->validate([
            "nombre"=>"required",
            "ubicacion"=>"required",
            "descripcion"=>"required",
            "presupuesto"=>"required",
            "fecha"=>"required",
            "pdfPlano"=>"nullable|file|mimes:pdf"
        ]);

        $this->validate([
            'manzanas'=>'required',
            'manzanas.*.nombreMz'=>'required',
            'manzanas.*.descripcionMz'=>'required',
            'manzanas.*.lotes'=>'required',
            'manzanas.*.lotes.*.numLote'=>'required',
            'manzanas.*.lotes.*.areaLote'=>'required',
            'manzanas.*.lotes.*.precioLote'=>'required'
        ]);

        $proyecto = Proyecto::find($this->id_proyecto);
        $proyecto->nom_proyecto=$this->nombre;
        $proyecto->ubi_proyecto=$this->ubicacion;
        $proyecto->descripcion_proyecto=$this->descripcion;
        $proyecto->presupuesto_proyecto=$this->presupuesto;
        $proyecto->fecha_proyecto=$this->fecha;
        if ($this->pdfPlano) {
            $pdfPath = $this->pdfPlano->store('planosPdf', 'public');
            $proyecto->pdf_ruta_proyecto = $pdfPath;
        }
        $proyecto->save();

        foreach ($this->manzanas as $key => $manzana) {
            $existManzana = isset($manzana['id_manzana']) 
                ? Manzana::find($manzana['id_manzana']) 
                : new Manzana();
        
            $existManzana->id_proyecto = $this->id_proyecto;
            $existManzana->nom_manzana = $manzana['nombreMz'];
            $existManzana->descr_manzana = $manzana['descripcionMz'];
            if (!isset($manzana['id_manzana'])) {
                $existManzana->est_manzana = $manzana['estadoMz'];
            }
            $existManzana->save();
        
            foreach($manzana['lotes'] as $loteKey => $lote){
                $existLote = isset($lote['id_lote']) 
                    ? Lote::find($lote['id_lote']) 
                    : new Lote();
        
                $existLote->id_manzana = $existManzana->id_manzana;
                $existLote->nom_lote = $lote['numLote'];
                $existLote->area_lote = $lote['areaLote'];
                $existLote->precio_lote = $lote['precioLote'];
                if (!isset($lote['id_lote'])) {
                    $existLote->est_lote = $lote['estLote'];
                }
                $existLote->save();
            }
        }

        Flux::modal("editar-proyecto")->close();
        $this->reset('manzanas');
        $this->dispatch("reloadProyectos");
    }
}
