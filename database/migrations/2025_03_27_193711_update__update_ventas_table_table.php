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
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['id_proyecto']);
            $table->dropColumn('id_proyecto');
            $table->unsignedBigInteger('id_lote')->nullable()->after('id_venta');

            $table->foreign('id_lote')->references('id_lote')->on('lote')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropForeign(['id_lote']);
            $table->dropColumn('id_lote');
            $table->unsignedBigInteger('id_proyecto')->nullable()->after('id_venta');

            $table->foreign('id_proyecto')->references('id_proyecto')->on('proyecto')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
