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
        Schema::create('activivity_centros', function (Blueprint $table) {
            $table->id();

            $table->foreignId('activity_id')->constrained('activities');
            $table->foreignId('centro_id')->constrained('centro_civicos');

            $table->date('fecha');
            $table->time('horario_inicio');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activivity_centros');
    }
};
