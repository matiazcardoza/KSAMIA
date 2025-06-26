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
            $table->dropForeign('ventas_id_tipo_usuario_foreign');
            $table->dropColumn('id_tipo_usuario');
            $table->unsignedBigInteger('id_usuario_venta')->nullable()->after('id_tipo_venta');
            $table->foreign('id_usuario_venta')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function(Blueprint $table){
            $table->dropForeign('ventas_id_usuario_venta_foreign');
            $table->dropColumn('id_usuario_ventas');
            
            $table->unsignedBigInteger('id_tipo_usuario')->nullable()->after('id_tipo_venta');
            $table->foreign('id_tipo_usuario')->references('id_tipo_usuario')->on('tipo_usuario')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
