<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id('id_cliente');
            $table->integer('dni_cliente_venta')->nullable()->unique();
            $table->longText('nom_cliente')->nullable();
            $table->longText('ape_cliente')->nullable();
            $table->longText('email_cliente')->nullable();
            $table->integer('tel_cliente')->nullable();
            $table->longText('direccion_cliente')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
