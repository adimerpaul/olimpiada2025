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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('area')->nullable();
            $table->string('curso1')->nullable();
            $table->string('curso2')->nullable();
            $table->string('curso3')->nullable();
            $table->string('curso4')->nullable();
            $table->string('curso5')->nullable();
            $table->string('curso6')->nullable();
            $table->string('titulo_fecha1')->nullable();
            $table->date('fecha1')->nullable();
            $table->string('titulo_fecha2')->nullable();
            $table->date('fecha2')->nullable();
            $table->text('lugar')->nullable();
            $table->string('modalidad')->nullable();
            $table->text('inscripcion')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
