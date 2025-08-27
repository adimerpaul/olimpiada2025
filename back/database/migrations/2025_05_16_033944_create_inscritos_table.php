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
        Schema::create('inscritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade'); // Área a la que pertenece el grupo
            $table->string('grupo_nombre')->nullable(); // Nombre del grupo o equipo
            $table->unsignedInteger('max_integrantes')->default(10); // Máximo permitido

            // Datos de estudiantes
            for ($i = 1; $i <= 10; $i++) {
                $table->string("estudiante{$i}")->nullable();
                $table->string("ci{$i}")->nullable();
                $table->string("tutor{$i}")->nullable();
                $table->string("telefono{$i}")->nullable();
                $table->string("curso{$i}")->nullable();
            }

            $table->string('pago1')->nullable();
            $table->string('tutor')->nullable();
            $table->string('colegio')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscritos');
    }
};
