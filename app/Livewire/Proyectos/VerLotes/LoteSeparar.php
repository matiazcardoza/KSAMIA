<?php

namespace App\Livewire\Proyectos\VerLotes;

use App\Models\Cliente;
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
        $this->validate([
            "dniSeparar"=>"required|digits:8",
            "nomSeparar"=>"required",
            "apeSeparar"=>"required",
            "emailSeparar"=>"required",
            "telVenta"=>"required",
            "dirVenta"=>"required",
            "fechaVenta"=>"required"
        ]);
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
