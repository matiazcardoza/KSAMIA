<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inversor extends Model
{
    protected $table = 'inversores';
    protected $primaryKey = 'id_inversor';
    protected $fillable = [
        "id_proyecto",
        "nom_inversor",
        "email_inversor",
        "tel_inversor",
        "monto_inversor",
        "porcentaje_inversor",
        "fecha_inversor"
    ];
}
