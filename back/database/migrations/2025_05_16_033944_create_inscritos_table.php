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
            $table->string('estudiante1')->nullable();
            $table->string('estudiante2')->nullable();
            $table->string('estudiante3')->nullable();
            $table->string('estudiante4')->nullable();
            $table->string('estudiante5')->nullable();
            $table->string('estudiante6')->nullable();
            $table->string('estudiante7')->nullable();
            $table->string('estudiante8')->nullable();
            $table->string('estudiante9')->nullable();
            $table->string('estudiante10')->nullable();
            $table->string('ci1')->nullable();
            $table->string('ci2')->nullable();
            $table->string('ci3')->nullable();
            $table->string('ci4')->nullable();
            $table->string('ci5')->nullable();
            $table->string('ci6')->nullable();
            $table->string('ci7')->nullable();
            $table->string('ci8')->nullable();
            $table->string('ci9')->nullable();
            $table->string('ci10')->nullable();
            $table->string('tutor1')->nullable();
            $table->string('tutor2')->nullable();
            $table->string('tutor3')->nullable();
            $table->string('tutor4')->nullable();
            $table->string('tutor5')->nullable();
            $table->string('tutor6')->nullable();
            $table->string('tutor7')->nullable();
            $table->string('tutor8')->nullable();
            $table->string('tutor9')->nullable();
            $table->string('tutor10')->nullable();
            $table->string('telefono1')->nullable();
            $table->string('telefono2')->nullable();
            $table->string('telefono3')->nullable();
            $table->string('telefono4')->nullable();
            $table->string('telefono5')->nullable();
            $table->string('telefono6')->nullable();
            $table->string('telefono7')->nullable();
            $table->string('telefono8')->nullable();
            $table->string('telefono9')->nullable();
            $table->string('telefono10')->nullable();
//            curso 1-10
            $table->string('curso1')->nullable();
            $table->string('curso2')->nullable();
            $table->string('curso3')->nullable();
            $table->string('curso4')->nullable();
            $table->string('curso5')->nullable();
            $table->string('curso6')->nullable();
            $table->string('curso7')->nullable();
            $table->string('curso8')->nullable();
            $table->string('curso9')->nullable();
            $table->string('curso10')->nullable();
            $table->string('pago1')->nullable();
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
