<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    protected $table = 'evidencias';
    protected $primaryKey = 'id_evidencia';
    protected $fillable = [
        "id_proyecto",
        "ruta_evidencia"
    ];
}
