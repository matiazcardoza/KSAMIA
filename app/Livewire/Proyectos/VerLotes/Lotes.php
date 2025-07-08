<?php

namespace App\Livewire\Proyectos\VerLotes;

use App\Models\Lote;
use App\Models\Manzana;
use App\Models\Proyecto;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class Lotes extends Component
{
    public $id_proyecto, $nombre, $ubicacion, $descripcion, $presupuesto, $fecha, $manzanas=[];

    public function descargarContratoPDF($id_lote)
    {
        $lote = Lote::select('lote.*', 'manzana.nom_manzana', 'manzana.descr_manzana', 'proyecto.nom_proyecto', 'proyecto.ubi_proyecto', 'ventas.id_venta', 'clientes.nom_cliente', 'clientes.ape_cliente')
            ->join('manzana', 'manzana.id_manzana', '=', 'lote.id_manzana')
            ->join('proyecto', 'proyecto.id_proyecto', '=', 'manzana.id_proyecto')
            ->leftJoin('ventas', 'ventas.id_lote', '=', 'lote.id_lote')
            ->leftJoin('clientes', 'clientes.id_cliente', '=', 'ventas.id_cliente_venta')
            ->where('lote.id_lote', '=', $id_lote)
            ->first();

        $datos = [
            'proyecto' => [
                'nombre' => $lote->nom_proyecto,
                'ubicacion' => $lote->ubi_proyecto,
                'descripcion' => $lote->descripcion_proyecto,
            ],
            'manzana' => [
                'nombre' => $lote->nom_manzana,
                'descripcion' => $lote->descr_manzana,
            ],
            'lote' => [
                'numero' => $lote->nom_lote,
                'area' => $lote->area_lote,
                'precio' => $lote->precio_lote,
            ],
            'venta' => $lote->venta,
            'cliente' => $lote->cliente ?? (object)[
                'nombre' => 'N/A',
                'documento' => 'N/A',
                'telefono' => 'N/A',
                'email' => 'N/A'
            ],
        ];

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.contrato-lote', $datos);
        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream('contrato_lote_' . $lote->nom_lote . '.pdf');
    }

    public function render()
    {
        return view('livewire.proyectos.ver-lotes.lotes');
    }
    public function mount($id_proyecto=null){
        $this->reset('manzanas');
        $proyecto = Proyecto::find($id_proyecto);
        Log::info("Cargando lotes del proyecto: $proyecto");
        $this->id_proyecto=$id_proyecto;
        $this->nombre=$proyecto->nom_proyecto;
        $this->ubicacion=$proyecto->ubi_proyecto;
        $this->descripcion=$proyecto->descripcion_proyecto;
        $this->presupuesto=$proyecto->presupuesto_proyecto;
        $this->fecha=$proyecto->fecha_proyecto;

        $manzanas=Manzana::where('id_proyecto', $id_proyecto)->get();

        $this->manzanas = $manzanas->map(function ($manzana) {
            $lotes = Lote::select(
                    'lote.id_lote',
                    'lote.nom_lote',
                    'lote.area_lote',
                    'lote.precio_lote',
                    'lote.est_lote',
                    'ventas.est_venta'
                )->where('id_manzana', $manzana->id_manzana)
                ->leftJoin('ventas', 'ventas.id_lote', '=', 'lote.id_lote')
                ->get();

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
                        'estadoVenta' => $lote->est_venta,
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

    public function editar($id_lote, $id_proyecto){
        $this->dispatch("EditarLote", $id_lote, $id_proyecto);
    }

    #[On("reloadLotes")]
    function realoadLotes(){
        $manzanas = Manzana::where('id_proyecto', $this->id_proyecto)->get();
        $this->manzanas = $manzanas->map(function ($manzana) {
            $lotes = Lote::select(
                    'lote.id_lote',
                    'lote.nom_lote',
                    'lote.area_lote',
                    'lote.precio_lote',
                    'lote.est_lote',
                    'ventas.est_venta'
                )->where('id_manzana', $manzana->id_manzana)
                ->leftJoin('ventas', 'ventas.id_lote', '=', 'lote.id_lote')
                ->get();

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
                        'estadoVenta' => $lote->est_venta,
                        'estado' => $lote->est_lote
                    ];
                })->toArray()
            ];
        })->toArray();
    }
}
