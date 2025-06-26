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
        Schema::table('clientes', function (Blueprint $table) {
            $table->renameColumn('dni_cliente_venta', 'dni_cliente');
            $table->renameColumn('direccion_cliente', 'dir_cliente');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->renameColumn('dni_cliente', 'dni_cliente_venta');
            $table->renameColumn('dir_cliente', 'direccion_cliente');
        });
    }
};
