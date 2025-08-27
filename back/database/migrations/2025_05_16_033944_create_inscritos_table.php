<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscritos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('area_id')
                ->constrained('areas')
                ->onDelete('cascade');

            $table->string('grupo_nombre', 255)->nullable();
            $table->unsignedInteger('max_integrantes')->default(10);

            // 10 slots de estudiantes, con tamaños compactos
            for ($i = 1; $i <= 10; $i++) {
                $table->string("estudiante_nombre{$i}", 100)->nullable();
                $table->string("estudiante_paterno{$i}", 100)->nullable();
                $table->string("ci{$i}", 20)->nullable();
                $table->string("tutor{$i}", 100)->nullable();
                $table->string("telefono{$i}", 20)->nullable();
                $table->string("curso{$i}", 40)->nullable();
            }

            // Comprobante y datos del tutor/UE
            $table->string('pago1', 255)->nullable();

            $table->string('tutor_paterno', 100)->nullable();
            $table->string('tutor_materno', 100)->nullable();
            $table->string('tutor_nombre', 100)->nullable();
            $table->string('tutor_celular', 20)->nullable();
            $table->string('tutor_correo', 191)->nullable();

            $table->string('colegio', 150)->nullable();
            $table->string('ciudad', 100)->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscritos');
    }
};
