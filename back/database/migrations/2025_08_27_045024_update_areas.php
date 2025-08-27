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
        Schema::table('areas', function (Blueprint $table) {
//            $table->enum('modalidad', ['individual','grupo'])->default('individual')->after('area');
            $table->unsignedTinyInteger('min_integrantes')->default(1)->after('modalidad');
            $table->unsignedTinyInteger('max_integrantes')->default(10)->after('min_integrantes');
            $table->boolean('grupo_mismo_curso')->default(false)->after('max_integrantes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn(['min_integrantes','max_integrantes','grupo_mismo_curso']);
        });
    }
};
