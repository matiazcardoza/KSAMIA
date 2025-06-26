<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    protected $primaryKey = 'id_cliente';
    protected $fillable = [
        "dni_cliente",
        "nom_cliente",
        "ape_cliente",
        "email_cliente",
        "tel_cliente",
        "dir_cliente",
        "est_cliente"
    ];
}
