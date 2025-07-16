<?php

namespace App\Livewire\Ventas;

use App\Models\Proyecto;
use App\Models\Tipo_venta;
use App\Models\Venta;
use Flux\Flux;
use Livewire\Component;

class Ventas extends Component
{
    public $proyecto, $id_proyecto;
    public $ventas, $id_venta, $venta;
    public $filtro_activo = 'todos';

    public function filtrarTodos()
    {
        $this->filtro_activo = 'todos';
        $this->cargarVentas();
    }

    public function filtrarPorEscritura()
    {
        $this->filtro_activo = 'escritura';
        $this->cargarVentas();
    }

    public function filtrarPorCuota()
    {
        $this->filtro_activo = 'cuota';
        $this->cargarVentas();
    }

    public function filtrarSeparados()
    {
        $this->filtro_activo = 'separados';
        $this->cargarVentas();
    }

    public function mount($id_proyecto = null)
    {
        $this->id_proyecto = $id_proyecto;
        $this->proyecto = Proyecto::where('id_proyecto', $this->id_proyecto)->get();
        $this->cargarVentas();
    }

    public function cargarVentas(){
        $query = Venta::select([
            'ventas.*',
            'lote.nom_lote',
            'lote.area_lote',
            'lote.precio_lote',
            'lote.est_lote',
            'manzana.nom_manzana',
            'proyecto.nom_proyecto',
            'proyecto.ubi_proyecto',
            'proyecto.id_proyecto'
        ])
        ->leftJoin('lote', 'ventas.id_lote', '=', 'lote.id_lote')
        ->leftJoin('manzana', 'lote.id_manzana', '=', 'manzana.id_manzana')
        ->leftJoin('proyecto', 'manzana.id_proyecto', '=', 'proyecto.id_proyecto')
        ->where('proyecto.id_proyecto', $this->id_proyecto);
        switch ($this->filtro_activo) {
            case 'escritura':
                $query->where('ventas.id_tipo_venta', 1);
                break;
            case 'cuota':
                $query->where('ventas.id_tipo_venta', 2);
                break;
            case 'separados':
                $query->where('ventas.est_venta', 3);
                break;
            case 'todos':
            default:
                break;
        }
        $this->ventas = $query->get();
    }

    public function eliminar($id_venta)
    {
        $this->id_venta = $id_venta;
        $this->cargarVentas();
        Flux::modal("eliminar-venta")->show();
    }

    public function verVenta($id_venta)
    {
        $this->id_venta = $id_venta;
        $this->venta = Venta::select([
            'ventas.*',
            'lote.nom_lote',
            'lote.area_lote',
            'lote.precio_lote',
            'lote.est_lote',
            'manzana.nom_manzana',
            'proyecto.nom_proyecto',
            'proyecto.ubi_proyecto'
        ])
        ->leftJoin('lote', 'ventas.id_lote', '=', 'lote.id_lote')
        ->leftJoin('manzana', 'lote.id_manzana', '=',   'manzana.id_manzana')
        ->leftJoin('proyecto', 'manzana.id_proyecto', '=', 'proyecto.id_proyecto')
        ->where('ventas.id_venta', $this->id_venta)
        ->first();
        $this->cargarVentas();
        Flux::modal("ver-venta")->show();
    }

    public function destroy(){
        $venta = Venta::find($this->id_venta);
        if ($venta) {
            $lote = $venta->lote;
            if ($lote) {
                $lote->est_lote = 1; // Reiniciar estado del lote a disponible
                $lote->save();
            }
            $venta->delete();
        }
        $this->cargarVentas();
        Flux::modal("eliminar-venta")->close();
        $this->dispatch("reloadMenuVentas");
    }

    public function editar($id_lote, $id_proyecto){
        $this->cargarVentas();
        $this->dispatch("EditarLote", $id_lote, $id_proyecto);
    }

    public function separarEditar($id_lote){
        $this->cargarVentas();
        $this->dispatch("SepararEditarLote", $id_lote);
    }

    public function render()
    {
        return view('livewire.ventas.ventas');
    }
}
