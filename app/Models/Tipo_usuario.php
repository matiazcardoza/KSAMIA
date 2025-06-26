<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_usuario extends Model
{
    protected $table = 'tipo_usuario';
    protected $primaryKey = 'id_tipo_usuario';
    protected $fillable = ["nom_tipo_usuario", "est_tipo_usuario"];
}
