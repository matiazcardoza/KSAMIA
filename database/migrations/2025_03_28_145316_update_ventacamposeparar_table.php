<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Livewire\after;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->decimal('mseparado_venta', 15, 2)->nullable()->after('monto_venta');
            $table->date('fecseparar_venta')->nullable()->after('fecha_venta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn('mseparado_venta');
            $table->dropColumn('fecseparar_venta');
        });
        
    }
};
