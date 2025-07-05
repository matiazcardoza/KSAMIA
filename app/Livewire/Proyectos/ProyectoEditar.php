<?php

namespace App\Livewire\Proyectos;

use App\Models\Evidencia;
use App\Models\Inversor;
use App\Models\Lote;
use App\Models\Manzana;
use App\Models\Proyecto;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProyectoEditar extends Component
{
    use WithFileUploads;

    public $id_proyecto, $nombre, $ubicacion, $descripcion, $presupuesto, $manzanas=[], $fecha, $pdfPlano, $planoActual;

    public $descripcionMz, $areaLote, $precioLote;

    public $presuDolar, $usarDolar;

    public $inversoresActuales = [];

    public $evidenciasActuales = [];

    public $evidencias = [];

    public function agregarinversor(){
        $this->inversoresActuales[] = [
            'nombreInversor' => '',
            'emailInversor' => '',
            'telInversor' => '',
            'porcentajeInversor' => '',
            'montoInversor' => '',
            'fechaInversor' => date('Y-m-d')
        ];
    }

    public function eliminarInversor($key){

        if (isset($this->inversoresActuales[$key]['id_inversor'])) {
            $inversorId = $this->inversoresActuales[$key]['id_inversor'];     
            Inversor::where('id_inversor', $inversorId)->delete();
        }

        unset($this->inversoresActuales[$key]);
        $this->inversoresActuales = array_values($this->inversoresActuales);
    }

    public function calcularPorcentaje($key)
    {
        $presupuestoBase = $this->usarDolar ? $this->presuDolar : $this->presupuesto;
        if ($presupuestoBase > 0 && isset($this->inversoresActuales[$key]['montoInversor']) && $this->inversoresActuales[$key]['montoInversor'] > 0) {
            $this->inversoresActuales[$key]['porcentajeInversor'] = round(($this->inversoresActuales[$key]['montoInversor'] / $presupuestoBase) * 100, 2);
        }
    }

    public function updatedInversoresActuales($value, $key)
    {
        $keyParts = explode('.', $key);
        if (count($keyParts) === 2 && $keyParts[1] === 'montoInversor') {
            $inversorIndex = $keyParts[0];
            $this->calcularPorcentaje($inversorIndex);
        }
    }

    public function updatedPresupuesto()
    {
        foreach ($this->inversoresActuales as $key => $inversor) {
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
        $this->reset('inversoresActuales');
        $this->reset('manzanas');
        $proyecto = Proyecto::find($id_proyecto);
        $this->id_proyecto=$id_proyecto;
        $this->nombre=$proyecto->nom_proyecto;
        $this->ubicacion=$proyecto->ubi_proyecto;
        $this->descripcion=$proyecto->descripcion_proyecto;
        $this->presupuesto=$proyecto->presupuesto_proyecto;
        $this->presuDolar=$proyecto->presuDolar_proyecto;
        $this->usarDolar = !empty($proyecto->presuDolar_proyecto);
        $this->fecha=$proyecto->fecha_proyecto;
        $this->planoActual = $proyecto->pdf_ruta_proyecto;

        $this->evidenciasActuales = Evidencia::where('id_proyecto', $id_proyecto)->get();

        $inversores=Inversor::where('id_proyecto', $id_proyecto)->get();

        foreach ($inversores as $inversor){
            $inversorData=[
                'id_inversor'=>$inversor->id_inversor,
                'nombreInversor'=>$inversor->nom_inversor,
                'emailInversor'=>$inversor->email_inversor,
                'telInversor'=>$inversor->tel_inversor,
                'montoInversor'=>$inversor->monto_inversor,
                'porcentajeInversor'=>$inversor->porcentaje_inversor,
                'fechaInversor'=>$inversor->fecha_inversor
            ];

            $this->inversoresActuales[]=$inversorData;
        }

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

    public function eliminarEvidencia($id)
    {
        $evidencia = Evidencia::find($id);
        if ($evidencia) {
            Storage::disk('public')->delete($evidencia->ruta_evidencia);
            $evidencia->delete();
            $this->evidenciasActuales = Evidencia::where('id_proyecto', $this->id_proyecto)->get();
        }
    }

    public function update(){
        $this->validate([
            "nombre"=>"required",
            "ubicacion"=>"required",
            "descripcion"=>"required",
            "presupuesto" => "required_without:presuDolar",
            "presuDolar" => "required_without:presupuesto",
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

        foreach ($this->inversoresActuales as $inversor){
            $existInversor = isset($inversor['id_inversor']) ? Inversor::find($inversor['id_inversor']) : new Inversor();
                $existInversor->id_proyecto = $this->id_proyecto;
                $existInversor->nom_inversor = $inversor['nombreInversor'];
                $existInversor->email_inversor = $inversor['emailInversor'];
                $existInversor->tel_inversor = $inversor['telInversor'];
                $existInversor->monto_inversor = $inversor['montoInversor'];
                $existInversor->porcentaje_inversor = $inversor['porcentajeInversor'];
                $existInversor->fecha_inversor = $inversor['fechaInversor'];
            $existInversor->save();
        }

        $presuDolarFinal = $this->usarDolar ? (float) $this->presuDolar : null;
        $presupuestoFinal = $this->usarDolar ? null : (float) $this->presupuesto;

        $proyecto = Proyecto::find($this->id_proyecto);

        $carpetaActual = $this->sanitizarNombreCarpeta($proyecto->nom_proyecto);
        $carpetaNueva = $this->sanitizarNombreCarpeta($this->nombre);
        $nombreCambio = $carpetaActual !== $carpetaNueva;

        if ($nombreCambio) {
            $this->moverArchivosACarpetaNueva($carpetaActual, $carpetaNueva, $proyecto);
        }

            $proyecto->nom_proyecto=$this->nombre;
            $proyecto->ubi_proyecto=$this->ubicacion;
            $proyecto->descripcion_proyecto=$this->descripcion;
            $proyecto->presupuesto_proyecto=$presupuestoFinal;
            $proyecto->presuDolar_proyecto=$presuDolarFinal;
            $proyecto->fecha_proyecto=$this->fecha;
            if ($this->pdfPlano) {
                $nombreOriginalPlano = $this->pdfPlano->getClientOriginalName();
                if ($proyecto->pdf_ruta_proyecto) {
                    Storage::disk('public')->delete($proyecto->pdf_ruta_proyecto);
                }
                $pdfPath = $this->pdfPlano->storeAs("{$carpetaNueva}/planosPdf", $nombreOriginalPlano, 'public');
                $proyecto->pdf_ruta_proyecto = $pdfPath;
            }

            if (!empty($this->evidencias)) {
                foreach ($this->evidencias as $archivo) {
                    $nombreOriginalEvidencia = $archivo->getClientOriginalName();
                    $ruta = $archivo->storeAs("{$carpetaNueva}/evidencias", $nombreOriginalEvidencia, 'public');
                    
                    Evidencia::create([
                        'id_proyecto' => $proyecto->id_proyecto,
                        'ruta_evidencia' => $ruta
                    ]);
                }
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
        $this->reset('inversoresActuales');
        $this->reset('evidencias');
        $this->dispatch("reloadProyectos");
    }

    private function sanitizarNombreCarpeta($nombre)
    {
        $nombre = preg_replace('/[^a-zA-Z0-9\s]/', '', $nombre);
        $nombre = preg_replace('/\s+/', '_', trim($nombre));
        $nombre = strtolower($nombre);
        $nombre = substr($nombre, 0, 50);
        return $nombre;
    }

    private function moverArchivosACarpetaNueva($carpetaActual, $carpetaNueva, $proyecto)
    {
        if ($carpetaActual === $carpetaNueva) {
            return;
        }
        if ($proyecto->pdf_ruta_proyecto && Storage::disk('public')->exists($proyecto->pdf_ruta_proyecto)) {
                $nombreArchivo = basename($proyecto->pdf_ruta_proyecto);
                $nuevaRutaPlano = "{$carpetaNueva}/planosPdf/{$nombreArchivo}";
    
                Storage::disk('public')->makeDirectory("{$carpetaNueva}/planosPdf");

                Storage::disk('public')->move($proyecto->pdf_ruta_proyecto, $nuevaRutaPlano);

                $proyecto->pdf_ruta_proyecto = $nuevaRutaPlano;
            }

            $evidencias = Evidencia::where('id_proyecto', $proyecto->id_proyecto)->get();
            foreach ($evidencias as $evidencia) {
                if (Storage::disk('public')->exists($evidencia->ruta_evidencia)) {
                    $nombreArchivo = basename($evidencia->ruta_evidencia);
                    $nuevaRutaEvidencia = "{$carpetaNueva}/evidencias/{$nombreArchivo}";

                    Storage::disk('public')->makeDirectory("{$carpetaNueva}/evidencias");

                    Storage::disk('public')->move($evidencia->ruta_evidencia, $nuevaRutaEvidencia);

                    $evidencia->update(['ruta_evidencia' => $nuevaRutaEvidencia]);
                }
            }

            $this->eliminarCarpetaVacia($carpetaActual);
    }

    private function eliminarCarpetaVacia($carpeta)
    {
        $subcarpetas = ["{$carpeta}/planosPdf", "{$carpeta}/evidencias"];
            
        foreach ($subcarpetas as $subcarpeta) {
            if (Storage::disk('public')->exists($subcarpeta)) {
                $archivos = Storage::disk('public')->files($subcarpeta);
                if (empty($archivos)) {
                    Storage::disk('public')->deleteDirectory($subcarpeta);
                }
            }
        }

        if (Storage::disk('public')->exists($carpeta)) {
            $contenido = Storage::disk('public')->allFiles($carpeta);
            if (empty($contenido)) {
                Storage::disk('public')->deleteDirectory($carpeta);
            }
        }
    }
}
