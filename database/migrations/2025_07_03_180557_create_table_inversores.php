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
        Schema::create('inversores', function (Blueprint $table) {
            $table->id('id_inversor');
            $table->unsignedBigInteger('id_proyecto')->nullable();
            $table->longText('nom_inversor')->nullable();
            $table->longText('email_inversor')->nullable();
            $table->integer('tel_inversor')->nullable();
            $table->decimal('monto_inversor')->nullable();
            $table->decimal('porcentaje_inversor')->nullable();
            $table->date('fecha_inversor')->nullable();
            $table->foreign('id_proyecto')->references('id_proyecto')->on('proyecto')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inversores');
    }
};
