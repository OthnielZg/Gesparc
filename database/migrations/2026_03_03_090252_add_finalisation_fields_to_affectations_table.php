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
        Schema::table('affectations', function (Blueprint $table) {
            $table->date('date_remise')->nullable()->after('date_affectation');
            $table->string('lieu_remise')->nullable()->after('date_remise');
            $table->string('statut_bordereau')->default('brouillon')->after('lieu_remise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affectations', function (Blueprint $table) {
            $table->dropColumn(['date_remise', 'lieu_remise', 'statut_bordereau']);
        });
    }
};
