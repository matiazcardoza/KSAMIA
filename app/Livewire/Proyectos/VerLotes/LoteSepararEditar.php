<?php

namespace App\Livewire\Proyectos\VerLotes;

use App\Models\Cliente;
use App\Models\Venta;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;

class LoteSepararEditar extends Component
{
    public $dniCliente, $nomCliente, $apeCliente, $emailCliente, $telCliente, $dirCliente, $fechaSeparar, $montoSeparar;
    public $id_lote, $id_cliente_venta;

    public function update(){
        $this->nomCliente = strtoupper($this->nomCliente);
        $this->apeCliente = strtoupper($this->apeCliente);
        $this->dirCliente = strtoupper($this->dirCliente);

        $existCliente = isset($this->id_cliente_venta) && $this->id_cliente_venta
            ? Cliente::find($this->id_cliente_venta)
            : new Cliente();

        $existCliente->dni_cliente = $this->dniCliente;
        $existCliente->nom_cliente = $this->nomCliente;
        $existCliente->ape_cliente = $this->apeCliente;
        $existCliente->email_cliente = $this->emailCliente;
        $existCliente->tel_cliente = $this->telCliente;
        $existCliente->dir_cliente = $this->dirCliente;
        $existCliente->save();
        
        $existVenta = Venta::where('id_lote', $this->id_lote)
                        ->where('est_venta', 3)
                        ->first();
        $existVenta->fecseparar_venta = $this->fechaSeparar;
        $existVenta->mseparado_venta = $this->montoSeparar;
        $existVenta->save();
        
        Flux::modal("separar-editar-lote")->close();
        $this->dispatch("reloadLotes");
    }

    #[On("SepararEditarLote")]
    public function SepararEditarLote($id_lote){
        $this->id_lote = $id_lote;
        $venta = Venta::leftJoin('lote', 'ventas.id_lote', '=', 'lote.id_lote')
            ->leftJoin('clientes', 'ventas.id_cliente_venta', '=', 'clientes.id_cliente')
            ->where('lote.id_lote', $this->id_lote)->first();
        $this->dniCliente = $venta->dni_cliente;
        $this->nomCliente = $venta->nom_cliente;
        $this->apeCliente = $venta->ape_cliente;
        $this->emailCliente = $venta->email_cliente;
        $this->telCliente = $venta->tel_cliente;
        $this->dirCliente = $venta->dir_cliente;
        $this->fechaSeparar = $venta->fecseparar_venta;
        $this->montoSeparar = $venta->mseparado_venta;
        $this->id_cliente_venta = $venta->id_cliente_venta;
        Flux::modal("separar-editar-lote")->show();
    }

    public function render()
    {
        return view('livewire.proyectos.ver-lotes.lote-separar-editar');
    }
}
