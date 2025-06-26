<?php

namespace App\Livewire\Proyectos\VerLotes;

use App\Models\Cliente;
use App\Models\Lote;
use App\Models\Tipo_venta;
use App\Models\User;
use App\Models\Venta;
use App\Models\Proyecto;
use Flux\Flux;
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

    public function cargarPDF($id_proyecto)
    {
        $this->proyectoId=$id_proyecto;
        $proyecto = Proyecto::find($id_proyecto);

        if ($proyecto && $proyecto->pdf_ruta_proyecto) {
            $this->pdfUrl = Storage::url($proyecto->pdf_ruta_proyecto);
        }
    }





    public function mount(){
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
        // Verifica que el DNI tenga 8 dígitos
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
            // Si ya existe el cliente, sólo actualiza la información si ha cambiado
            $cliente = Cliente::find($this->id_cliente);
            $cliente->update([
                "nom_cliente" => $this->nomCliente,
                "ape_cliente" => $this->apeCliente,
                "email_cliente" => $this->emailCliente,
                "tel_cliente" => $this->telCliente,
                "dir_cliente" => $this->dirCliente
            ]);
        } else {
            // Crear nuevo cliente
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
            "est_venta"=>1
        ]);

        $lote = Lote::find($this->id_lote);
        if ($lote) {
            $lote->est_lote = 2;
            $lote->save();
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
        $this->fechaVenta="";
        $this->precioVenta="";
    }

    #[On("VenderLote")]
    public function VenderLote($id_lote, $id_proyecto){
        $this->id_lote=$id_lote;
        $this->resetForm();
        $this->cargarPDF($id_proyecto);
        Flux::modal("vender-lote")->show();
    }

    public function guardarPDFModificado($dataUrl)
    {
        // Decodificar la data URL
        $encodedData = explode(',', $dataUrl)[1];
        $decodedData = base64_decode($encodedData);
        
        // Guardar el nuevo PDF
        $nombreArchivo = 'planos/modificado_' . time() . '.pdf';
        Storage::put('public/' . $nombreArchivo, $decodedData);
        
        // Actualizar la referencia en la base de datos
        $proyecto = Proyecto::find($this->proyectoId);
        if ($proyecto) {
            $proyecto->pdf_ruta_proyecto = 'public/' . $nombreArchivo;
            $proyecto->save();
        }
    }
}
