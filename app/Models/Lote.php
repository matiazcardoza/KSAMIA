<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table='lote';
    protected $primaryKey='id_lote';
    protected $fillable=[
        "id_manzana",
        "nom_lote",
        "area_lote",
        "precio_lote",
        "est_lote"
    ];
}
