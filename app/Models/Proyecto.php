<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyecto';
    protected $primaryKey = 'id_proyecto';
    protected $fillable = [
        "nom_proyecto",
        "ubi_proyecto",
        "descripcion_proyecto",
        "presupuesto_proyecto",
        "presuDolar_proyecto",
        "fecha_proyecto",
        "pdf_ruta_proyecto",
        "est_proyecto"
    ];
}
