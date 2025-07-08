<?php

namespace App\Livewire\Proyectos\VerLotes;

use App\Models\Cliente;
use App\Models\Lote;
use App\Models\Tipo_venta;
use App\Models\User;
use App\Models\Venta;
use App\Models\Proyecto;
use Exception;
use Flux\Flux;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class LoteVender extends Component
{    
    use WithFileUploads;

    public $id_lote;
    public $id_tipo_venta;
    public $id_cliente;
    public $tipo_venta=[], $asesor_venta=[];
    public $dniCliente, $nomCliente, $apeCliente, $emailCliente, $telCliente, $dirCliente, $fechaVenta, $precioVenta;
    public $tipoVenta=1, $asesorVenta, $cuotaVenta;
    public $pdfUrl, $proyectoId, $pdfModificado, $id_proyecto=null;
    
    // Nueva propiedad para almacenar las modificaciones del PDF
    public $pdfModifications = [];
    
    public function cargarPDF($id_proyecto)
    {
        $this->proyectoId = $id_proyecto;
        $proyecto = Proyecto::find($id_proyecto);
        $this->pdfUrl = Storage::url($proyecto->pdf_ruta_proyecto);
    }

    public function mount(){
        $this->fechaVenta = date('Y-m-d');
        $this->tipo_venta = Tipo_venta::all()->map(function($tipo) {
            return ['id' => $tipo->id_tipo_venta, 'label' => $tipo->nom_tipo_venta];
        })->toArray();

        $this->asesor_venta = User::all()->map(function($user) {
            return ['id' => $user->id, 'label' => $user->name];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.proyectos.ver-lotes.lote-vender');
    }

    public function buscarCliente()
    {
        if (strlen($this->dniCliente) == 8) {
            $cliente = Cliente::where('dni_cliente', $this->dniCliente)->first();
            if ($cliente) {
                $this->nomCliente = $cliente->nom_cliente;
                $this->apeCliente = $cliente->ape_cliente;
                $this->emailCliente = $cliente->email_cliente;
                $this->telCliente = $cliente->tel_cliente;
                $this->dirCliente = $cliente->dir_cliente;
                $this->id_cliente = $cliente->id_cliente;
            } else {
                $this->resetCamposCliente();
            }
        } else {
            $this->resetCamposCliente();
        }

        $this->dispatch('InitializePDF');
    }

    public function resetCamposCliente()
    {
        $this->nomCliente = '';
        $this->apeCliente = '';
        $this->emailCliente = '';
        $this->telCliente = '';
        $this->dirCliente = '';
        $this->id_cliente = null;
    }

    // Método para recibir las modificaciones del PDF desde JavaScript
    public function savePdfModifications($modifications)
    {
        $this->pdfModifications = $modifications;
    }

    public function vender(){
        $this->nomCliente = strtoupper($this->nomCliente);
        $this->apeCliente = strtoupper($this->apeCliente);
        $this->dirCliente = strtoupper($this->dirCliente);
        
        $this->validate([
            "dniCliente"=>"required|digits:8",
            "nomCliente"=>"required",
            "apeCliente"=>"required",
            "emailCliente"=>"required",
            "telCliente"=>"required",
            "dirCliente"=>"required",
            "fechaVenta"=>"required",
            "asesorVenta"=>"required",
            "tipoVenta"=>"required",
            "precioVenta"=>"required",
            "cuotaVenta"=>$this->tipoVenta==2 ? 'required':'nullable'
        ]);

        if ($this->id_cliente) {
            $cliente = Cliente::find($this->id_cliente);
            $cliente->update([
                "nom_cliente" => $this->nomCliente,
                "ape_cliente" => $this->apeCliente,
                "email_cliente" => $this->emailCliente,
                "tel_cliente" => $this->telCliente,
                "dir_cliente" => $this->dirCliente
            ]);
        } else {
            $cliente = Cliente::create([
                "dni_cliente" => $this->dniCliente,
                "nom_cliente" => $this->nomCliente,
                "ape_cliente" => $this->apeCliente,
                "email_cliente" => $this->emailCliente,
                "tel_cliente" => $this->telCliente,
                "dir_cliente" => $this->dirCliente,
                "est_cliente" => 1
            ]);
            $this->id_cliente = $cliente->id_cliente;
        }
        
        Venta::create([
            "id_lote"=>$this->id_lote,
            "id_tipo_venta"=>$this->tipoVenta,
            "id_usuario_venta"=>$this->asesorVenta,
            "id_cliente_venta"=>$this->id_cliente,
            "fecha_venta"=>$this->fechaVenta,
            "cantidadcuota_venta"=>$this->cuotaVenta,
            "monto_venta"=>$this->precioVenta,
            "est_venta"=>2,
        ]);

        // Guardar PDF modificado si hay cambios
        if (!empty($this->pdfModifications)) {
            $this->dispatch('generate-modified-pdf');
        }

        Flux::modal("vender-lote")->close();
        $this->resetForm();
        $this->dispatch("reloadLotes");
    }

    public function resetForm()
    {
        $this->dniCliente="";
        $this->nomCliente="";
        $this->apeCliente="";
        $this->emailCliente="";
        $this->telCliente="";
        $this->dirCliente="";
        $this->fechaVenta=date('Y-m-d');
        $this->precioVenta="";
        $this->pdfModifications = [];
    }

    #[On("VenderLote")]
    public function VenderLote($id_lote, $id_proyecto){
        $this->id_lote = $id_lote;
        $this->id_proyecto = $id_proyecto;
        $this->resetForm();
        
        // Cargar PDF ANTES de abrir el modal
        $this->cargarPDF($id_proyecto);
        
        // Abrir el modal
        Flux::modal("vender-lote")->show();
        
        $this->dispatch('InitializePDF');
    }

    public function guardarPDFModificado($dataUrl)
    {
        $encodedData = explode(',', $dataUrl)[1];
        $decodedData = base64_decode($encodedData);
        
        $nombreArchivo = 'planos/modificado_' . time() . '.pdf';
        Storage::put('public/' . $nombreArchivo, $decodedData);
        
        $proyecto = Proyecto::find($this->proyectoId);
        if ($proyecto) {
            $proyecto->pdf_ruta_proyecto = 'public/' . $nombreArchivo;
            $proyecto->save();
        }
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