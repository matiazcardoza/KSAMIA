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
        Schema::create('proyecto', function (Blueprint $table) {
            $table->id('id_proyecto'); // Campo id_proyecto como PRIMARY KEY con autoincremento
            $table->longText('nom_proyecto')->nullable(); // Campo nom_proyecto con longtext, puede ser nulo
            $table->longText('nlote_proyecto')->nullable(); // Campo nlote_proyecto con longtext, puede ser nulo
            $table->date('fecha_proyecto')->nullable(); // Campo fecha_pedido como date, puede ser nulo
            $table->integer('est_proyecto')->nullable(); // Campo est_pedido como int(11), puede ser nulo

            $table->timestamps(); // Los campos created_at y updated_at se agregan automáticamente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyecto'); // Elimina la tabla proyecto si existe
    }
};
