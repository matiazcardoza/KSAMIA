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
        Schema::create('manzana', function (Blueprint $table) {
            $table->id('id_manzana');
            $table->unsignedBigInteger('id_proyecto')->nullable();
            $table->longText('nom_manzana')->nullable();
            $table->longtext('descr_manzana')->nullable();
            $table->integer('est_manzana')->nullable();
            $table->timestamps();

            $table->foreign('id_proyecto')->references('id_proyecto')->on('proyecto')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manzana');
    }
};
