<?php

namespace App\Livewire\Proyectos\VerLotes;

use App\Models\Cliente;
use App\Models\Proyecto;
use App\Models\Tipo_venta;
use App\Models\User;
use App\Models\Venta;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;

class LoteEditar extends Component
{
    public $id_lote;
    public $tipo_venta=[], $asesor_venta=[];
    public $dniCliente, $nomCliente, $apeCliente, $emailCliente, $telCliente, $dirCliente, $fechaVenta, $precioVenta;
    public $tipoVenta=1, $asesorVenta, $cuotaVenta;
    public $pdfUrl, $proyectoId, $pdfModificado, $id_proyecto=null;
    public $id_cliente;

    public function mount() {
        $this->tipo_venta = Tipo_venta::all()->map(function($tipo) {
            return ['id' => $tipo->id_tipo_venta, 'label' => $tipo->nom_tipo_venta];
        })->toArray();      

        $this->asesor_venta = User::all()->map(function($user) {
            return ['id' => $user->id, 'label' => $user->name];
        })->toArray();
    }

    public function cargarPDF($id_proyecto)
    {
        $this->proyectoId = $id_proyecto;
        $proyecto = Proyecto::find($id_proyecto);
        $this->pdfUrl = Storage::url($proyecto->pdf_ruta_proyecto);
    }

    public function update(){
        $this->nomCliente = strtoupper($this->nomCliente);
        $this->apeCliente = strtoupper($this->apeCliente);
        $this->dirCliente = strtoupper($this->dirCliente);
        
        $this->validate([
            "dniCliente" => "required|digits:8",
            "nomCliente" => "required",
            "apeCliente" => "required",
            "emailCliente" => "required",
            "telCliente" => "required",
            "dirCliente" => "required",
            "fechaVenta" => "required",
            "asesorVenta" => "required",
            "tipoVenta" => "required",
            "precioVenta" => "required",
            "cuotaVenta" => $this->tipoVenta == 2 ? 'required' : 'nullable'
        ]);

        
        $existCliente = isset($this->id_cliente) && $this->id_cliente
            ? Cliente::find($this->id_cliente)
            : new Cliente();
        
        $existCliente->dni_cliente = $this->dniCliente;
        $existCliente->nom_cliente = $this->nomCliente;
        $existCliente->ape_cliente = $this->apeCliente;
        $existCliente->email_cliente = $this->emailCliente;
        $existCliente->tel_cliente = $this->telCliente;
        $existCliente->dir_cliente = $this->dirCliente;
        $existCliente->est_cliente = 1;
        $existCliente->save();
        
        $existVenta = Venta::where('id_lote', $this->id_lote)
                        ->where('est_venta', 2)
                        ->first();
        
        $existVenta->id_tipo_venta = $this->tipoVenta;
        $existVenta->id_usuario_venta = $this->asesorVenta;
        $existVenta->id_cliente_venta = $this->id_cliente;
        $existVenta->fecha_venta = $this->fechaVenta;
        $existVenta->cantidadcuota_venta = $this->cuotaVenta;
        $existVenta->monto_venta = $this->precioVenta;
        $existVenta->est_venta = 2;
        $existVenta->save();

        Flux::modal("editar-lote")->close();
        $this->dispatch("reloadLotes");
    }

    public function render()
    {
        return view('livewire.proyectos.ver-lotes.lote-editar');
    }

    #[On("EditarLote")]
    public function EditarLote($id_lote, $id_proyecto){
        $this->id_lote = $id_lote;
        $this->id_proyecto = $id_proyecto;
        
        $ventas = Venta::select('ventas.*', 
                                'clientes.dni_cliente', 
                                'clientes.nom_cliente',
                                'clientes.ape_cliente',
                                'clientes.email_cliente',
                                'clientes.tel_cliente',
                                'clientes.dir_cliente')
                                ->leftjoin('clientes', 'clientes.id_cliente', '=', 'ventas.id_cliente_venta')
                                ->where('id_lote', $this->id_lote)->first();
        $this->id_cliente = $ventas->id_cliente_venta;
        $this->dniCliente = $ventas->dni_cliente;
        $this->nomCliente = $ventas->nom_cliente;
        $this->apeCliente = $ventas->ape_cliente;
        $this->emailCliente = $ventas->email_cliente;
        $this->telCliente = $ventas->tel_cliente;
        $this->dirCliente = $ventas->dir_cliente;
        $this->fechaVenta = $ventas->fecha_venta;
        $this->asesorVenta = $ventas->id_usuario_venta;
        $this->tipoVenta = $ventas->id_tipo_venta; 
        $this->precioVenta = $ventas->monto_venta;

        $this->tipo_venta = Tipo_venta::all()->map(function($tipo) {
            return ['id' => $tipo->id_tipo_venta, 'label' => $tipo->nom_tipo_venta];
        })->toArray();      

        $this->asesor_venta = User::all()->map(function($user) {
            return ['id' => $user->id, 'label' => $user->name];
        })->toArray();
        
        if($this->tipoVenta == 2 && $ventas->cantidadcuota_venta) {
            $this->cuotaVenta = $ventas->cantidadcuota_venta;
            $this->calcularCuotas();
        }

        $this->cargarPDF($id_proyecto);
        
        Flux::modal("editar-lote")->show();
        
        $this->dispatch('InitializePDF');
    }

    public $cuotas = [];

    public function updatedPrecioVenta(){
        $this->calcularCuotas();
    }

    public function updatedCuotaVenta(){
        $this->calcularCuotas();
    }

    public function calcularCuotas(){
        $this->cuotas = [];
        if($this->tipoVenta ==2 && $this->precioVenta && $this->cuotaVenta > 0){
            $base = intdiv($this->precioVenta, $this->cuotaVenta);
            $resto = $this->precioVenta - ($base * $this->cuotaVenta);
            for ($i = 1; $i <= $this->cuotaVenta; $i++) {
                if ($i == $this->cuotaVenta) {
                    $this->cuotas[] = $base + $resto;
                } else {
                    $this->cuotas[] = $base;
                }
            }
        }
        $this->dispatch('InitializePDF');
    }
}
