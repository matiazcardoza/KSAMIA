<?php

namespace App\Livewire\Proyectos;

use App\Models\Lote;
use App\Models\Manzana;
use App\Models\Proyecto;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProyectoNuevo extends Component
{
    use WithFileUploads;

    public $nombre, $nrolote, $ubicacion, $descripcion, $presupuesto, $fecha, $pdfPlano;

    public $manzanas=[], $nombreMz, $descripcionMz, $numLote, $areaLote, $precioLote;

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
            'precioLote'=>$this->precioLote
        ];

        $this->areaLote='';
        $this->precioLote='';
    }

    public function eliminarManzana($key)
    {
        unset($this->manzanas[$key]);
        $this->manzanas = array_values($this->manzanas);
    }

    public function eliminarLote($key, $loteKey)
    {
        unset($this->manzanas[$key]['lotes'][$loteKey]);
        $this->manzanas[$key]['lotes'] = array_values($this->manzanas[$key]['lotes']);
    }

    public function render()
    {
        return view('livewire.proyectos.proyecto-nuevo');
    }

    public function submit(){
        $this->validate([
            "nombre"=>"required",
            "ubicacion"=>"required",
            "descripcion"=>"required",
            "presupuesto"=>"required",
            "fecha"=>"required",
            "pdfPlano"=>"required|file|mimes:pdf"
        ]);

        $pdfPath = $this->pdfPlano->store('planosPdf', 'public');

        $this->validate([
            'manzanas'=>'required',
            'manzanas.*.nombreMz'=>'required',
            'manzanas.*.descripcionMz'=>'required',
            'manzanas.*.lotes'=>'required',
            'manzanas.*.lotes.*.numLote'=>'required',
            'manzanas.*.lotes.*.areaLote'=>'required',
            'manzanas.*.lotes.*.precioLote'=>'required'
        ]);

        DB::beginTransaction();
        try{
            $proyecto=Proyecto::create([
                "nom_proyecto"=>$this->nombre,
                "ubi_proyecto"=>$this->ubicacion,
                "descripcion_proyecto"=>$this->descripcion,
                "presupuesto_proyecto"=>$this->presupuesto,
                "fecha_proyecto"=>$this->fecha,
                "pdf_ruta_proyecto"=>$pdfPath,
                "est_proyecto"=> 1
            ]);

            foreach ($this->manzanas as $manzanaData) {
                $manzana = Manzana::create([
                    "id_proyecto"=>$proyecto->id_proyecto,
                    "nom_manzana"=>$manzanaData['nombreMz'],
                    "descr_manzana"=>$manzanaData['descripcionMz'],
                    "est_manzana"=> 1
                ]);

                foreach ($manzanaData['lotes'] as $loteData) {
                    Lote::create([
                        "id_manzana"=>$manzana->id_manzana,
                        "nom_lote"=>$loteData['numLote'],
                        "area_lote"=>$loteData['areaLote'],
                        "precio_lote"=>$loteData['precioLote'],
                        "est_lote"=> 1
                    ]);
                }
            }

            DB::commit();

            $this->resetForm();
            Flux::modal("nuevo-proyecto")->close();
            $this->dispatch("reloadProyectos");

            session()->flash('message', 'Proyecto creado exitosamente');

        }catch(\Exception $e){
            DB::rollBack();
            session()->flash('error', 'Ocurrió un error: ' . $e->getMessage());
        }
    }

    public function resetForm(){
        $this->nombre="";
        $this->ubicacion="";
        $this->descripcion="";
        $this->presupuesto="";
        $this->fecha="";
        $this->manzanas=[];
    }
}
