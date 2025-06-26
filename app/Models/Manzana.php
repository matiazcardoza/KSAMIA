<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manzana extends Model
{
    protected $table='manzana';
    protected $primaryKey='id_manzana';
    protected $fillable=[
        "id_proyecto",
        "nom_manzana",
        "descr_manzana",
        "est_manzana"
    ];
}
