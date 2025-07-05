<?php

namespace App\Livewire\Proyectos;

use App\Models\Evidencia;
use App\Models\Inversor;
use App\Models\Lote;
use App\Models\Manzana;
use App\Models\Proyecto;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProyectoNuevo extends Component
{
    use WithFileUploads;

    public $nombre, $nrolote, $ubicacion, $descripcion, $presupuesto, $presuDolar, $fecha, $pdfPlano;

    public $manzanas=[], $nombreMz, $descripcionMz, $numLote, $areaLote, $precioLote;

    public $inversores = [], $porcentajeTotal = 0;

    public $evidencias = [];

    public $usarDolar = false;

    public function mount(){
        $this->fecha=date('Y-m-d');
    }

    public function agregarinversor(){
        $this->inversores[] = [
            'nombreInversor' => '',
            'emailInversor' => '',
            'telInversor' => '',
            'porcentajeInversor' => '',
            'montoInversor' => '',
            'fechaInversor' => date('Y-m-d')
        ];
    }

    public function eliminarInversor($key){
        Log:info("Eliminando inversor en la posición: $key");
        unset($this->inversores[$key]);
        $this->inversores = array_values($this->inversores);
    }

    public function calcularPorcentaje($key)
    {
        $presupuestoBase = $this->usarDolar ? $this->presuDolar : $this->presupuesto;
        if ($presupuestoBase > 0 && isset($this->inversores[$key]['montoInversor']) && $this->inversores[$key]['montoInversor'] > 0) {
            $this->inversores[$key]['porcentajeInversor'] = round(($this->inversores[$key]['montoInversor'] / $presupuestoBase) * 100, 2);
        }
    }

    public function updatedInversores($value, $key)
    {
        $keyParts = explode('.', $key);
        if (count($keyParts) === 2 && $keyParts[1] === 'montoInversor') {
            $inversorIndex = $keyParts[0];
            $this->calcularPorcentaje($inversorIndex);
        }
    }

    public function updatedPresupuesto()
    {
        foreach ($this->inversores as $key => $inversor) {
            if (isset($inversor['montoInversor']) && $inversor['montoInversor'] > 0) {
                $this->calcularPorcentaje($key);
            }
        }
    }

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
            "presupuesto" => "required_without:presuDolar",
            "presuDolar" => "required_without:presupuesto",
            "fecha"=>"required",
            "pdfPlano"=>"required|file|mimes:pdf"
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

        DB::beginTransaction();
        try{
            
            $presuDolarFinal = $this->usarDolar ? (float) $this->presuDolar : null;
            $presupuestoFinal = $this->usarDolar ? null : (float) $this->presupuesto;

            $proyecto=Proyecto::create([
                "nom_proyecto"=>$this->nombre,
                "ubi_proyecto"=>$this->ubicacion,
                "descripcion_proyecto"=>$this->descripcion,
                "presupuesto_proyecto" => $presupuestoFinal,
                "presuDolar_proyecto" => $presuDolarFinal,
                "fecha_proyecto"=>$this->fecha,
                "pdf_ruta_proyecto"=>"",
                "est_proyecto"=> 1
            ]);

            $carpetaProyecto = $this->sanitizarNombreCarpeta($this->nombre);
            $nombreOriginalPlano = $this->pdfPlano->getClientOriginalName();
            $pdfPath = $this->pdfPlano->storeAs("{$carpetaProyecto}/planosPdf", $nombreOriginalPlano, 'public');
            $proyecto->update(['pdf_ruta_proyecto' => $pdfPath]);

            foreach ($this->evidencias as $archivo) {
                $nombreOriginalEvidencia = $archivo->getClientOriginalName();
                $ruta = $archivo->storeAs("{$carpetaProyecto}/evidencias", $nombreOriginalEvidencia, 'public');
                Evidencia::create([
                    'id_proyecto' => $proyecto->id_proyecto,
                    'ruta_evidencia' => $ruta
                ]);
            }

            foreach ($this->inversores as $inversoresData){
                Inversor::create([
                    "id_proyecto"=>$proyecto->id_proyecto,
                    "nom_inversor"=>$inversoresData['nombreInversor'],
                    "email_inversor"=>$inversoresData['emailInversor'],
                    "tel_inversor"=>$inversoresData['telInversor'],
                    "porcentaje_inversor"=>$inversoresData['porcentajeInversor'],
                    "monto_inversor"=>$inversoresData['montoInversor'],
                    "fecha_inversor"=>$inversoresData['fechaInversor']
                ]);
            }

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

    private function sanitizarNombreCarpeta($nombre)
    {
        $nombre = preg_replace('/[^a-zA-Z0-9\s]/', '', $nombre);
        $nombre = preg_replace('/\s+/', '_', trim($nombre));
        $nombre = strtolower($nombre);
        $nombre = substr($nombre, 0, 50);
        return $nombre;
    }

    public function resetForm(){
        $this->nombre="";
        $this->ubicacion="";
        $this->descripcion="";
        $this->presupuesto="";
        $this->fecha=date('Y-m-d');
        $this->manzanas=[];
        $this->inversores=[];
        $this->pdfPlano="";
        $this->evidencias=[];
        $this->usarDolar="";
    }
}
