<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'id_venta';
    protected $fillable = [
        "id_lote",
        "id_tipo_venta",
        "id_usuario_venta",
        "id_cliente_venta",
        "fecha_venta",
        "fecseparar_venta",
        "cantidadcuota_venta",
        "monto_venta",
        "mseparado_venta",
        "est_venta"
    ];
}
