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
        Schema::table('proyecto', function (Blueprint $table) {
            $table->dropColumn('nlote_proyecto');
            $table->longText('ubi_proyecto')->nullable()->after('nom_proyecto');
            $table->longText('descripcion_proyecto')->nullable()->after('ubi_proyecto');
            $table->decimal('presupuesto_proyecto', 15, 2)->nullable()->after('descripcion_proyecto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyecto', function (Blueprint $table) {
            $table->dropColumn(['ubi_proyecto', 'descripcion_proyecto', 'presupuesto_proyecto']);
            $table->longText('nlote_proyecto')->nullable()->after('nom_proyecto');
        });
    }
};
