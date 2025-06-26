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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id('id_venta');
            $table->unsignedBigInteger('id_proyecto')->nullable();
            $table->unsignedBigInteger('id_tipo_venta')->nullable();
            $table->unsignedBigInteger('id_tipo_usuario')->nullable();
            $table->longText('nom_cliente_venta')->nullable();
            $table->longText('ape_cliente_venta')->nullable();
            $table->longText('email_cliente_venta')->nullable();
            $table->longText('tel_cliente_venta')->nullable();
            $table->longText('direccion_cliente_venta')->nullable();
            $table->integer('dni_cliente_venta')->nullable()->unique();
            $table->date('fecha_venta')->nullable();
            $table->integer('cantidadcuota_venta')->nullable();
            $table->decimal('monto_venta', 10, 2)->nullable();
            $table->integer('est_venta')->nullable();
            $table->timestamps();

            $table->foreign('id_proyecto')->references('id_proyecto')->on('proyecto')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_tipo_venta')->references('id_tipo_venta')->on('tipo_venta')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_tipo_usuario')->references('id_tipo_usuario')->on('tipo_usuario')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
