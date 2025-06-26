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
        Schema::create('tipo_usuario', function (Blueprint $table) {
            $table->id('id_tipo_usuario'); // Campo id_proyecto como PRIMARY KEY con autoincremento
            $table->longText('nom_tipo_usuario')->nullable(); // Campo nom_proyecto con longtext, puede ser nulo
            $table->integer('est_tipo_usuario')->nullable(); // Campo est_pedido como int(11), puede ser nulo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_usuario');
    }
};
