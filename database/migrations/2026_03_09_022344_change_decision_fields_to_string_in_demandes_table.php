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
        Schema::table('demandes', function (Blueprint $table) {
            $table->string('decision_sdgrte')->nullable()->change();
            $table->string('decision_dsi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->enum('decision_sdgrte', ['valide_sedmi', 'contre_propose'])->nullable()->change();
            $table->enum('decision_dsi', ['retient_sedmi', 'retient_sdrevt', 'autre'])->nullable()->change();
        });
    }
};
