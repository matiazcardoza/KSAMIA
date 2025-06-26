<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_venta extends Model
{
    protected $table = 'tipo_venta';
    protected $primaryKey = 'id_tipo_venta';
    protected $fillable = ["nom_tipo_venta", "est_tipo_venta"];
}
