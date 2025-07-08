<?php

namespace App\Livewire\Proyectos\VerLotes;

use App\Models\Cliente;
use App\Models\Lote;
use App\Models\Venta;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class LoteSeparar extends Component
{
    public $id_lote;

    //para seleccion de dni:
    public $dniCliente, $nomCliente, $apeCliente, $emailCliente, $telCliente, $dirCliente, $id_cliente;

    public $fechaSeparar, $montoSeparar;

    public function render()
    {
        return view('livewire.proyectos.ver-lotes.lote-separar');
    }

    public function separar(){

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
            "fechaSeparar"=>"required",
            "montoSeparar"=>"required|numeric|min:0",
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
            "id_cliente_venta"=>$this->id_cliente,
            "fecha_venta"=>$this->fechaSeparar,
            "mseparado_venta"=>$this->montoSeparar,
            "est_venta"=>3
        ]);

        Flux::modal("separar-lote")->close();
        $this->resetForm();
        $this->dispatch("reloadLotes");
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

    public function resetForm()
    {
        $this->dniCliente="";
        $this->nomCliente="";
        $this->apeCliente="";
        $this->emailCliente="";
        $this->telCliente="";
        $this->dirCliente="";
        $this->fechaSeparar="";
        $this->montoSeparar="";
    }

    #[On("SepararLote")]
    public function SepararLote($id_lote){

        $this->id_lote=$id_lote;
        $this->resetForm();
        Flux::modal("separar-lote")->show();
    }
}
