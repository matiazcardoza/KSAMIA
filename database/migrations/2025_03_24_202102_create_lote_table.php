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
        Schema::create('lote', function (Blueprint $table) {
            $table->id('id_lote');
            $table->unsignedBigInteger('id_manzana')->nullable();
            $table->longText('nom_lote')->nullable();
            $table->decimal('area_lote', 15, 2)->nullable();
            $table->decimal('precio_lote', 15, 2)->nullable();
            $table->integer('est_lote')->nullable();
            $table->timestamps();
            
            $table->foreign('id_manzana')->references('id_manzana')->on('manzana')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lote');
    }
};
