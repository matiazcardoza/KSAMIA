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
        Schema::table('ventas', function(Blueprint $table){
            $table->dropUnique('ventas_dni_cliente_venta_unique');
            $table->dropColumn('dni_cliente_venta');
            $table->dropColumn('nom_cliente_venta');
            $table->dropColumn('ape_cliente_venta');
            $table->dropColumn('email_cliente_venta');
            $table->dropColumn('tel_cliente_venta');
            $table->dropColumn('direccion_cliente_venta');
            $table->unsignedBigInteger('id_cliente_venta')->nullable()->after('id_usuario_venta');
            $table->foreign('id_cliente_venta')->references('id_cliente')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            
            $table->integer('dni_cliente_venta')->nullable();
            $table->longText('nom_cliente_venta')->nullable();
            $table->longText('ape_cliente_venta')->nullable();
            $table->longText('email_cliente_venta')->nullable();
            $table->integer('tel_cliente_venta')->nullable();
            $table->longText('direccion_cliente_venta')->nullable();
            $table->dropForeign('ventas_id_cliente_venta_foreign');
            $table->unique('dni_cliente_venta');
            $table->dropColumn('id_cliente_venta');
        });
    }
};
