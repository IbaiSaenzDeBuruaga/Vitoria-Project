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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('imagen')->nullable();
            $table->integer('edad_min');
            $table->integer('edad_max');
            $table->enum('horario', ['matutino', 'vespertino']);
            $table->enum('idioma', ['es', 'en', 'eu'])->default('es');
            $table->integer('plazas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
