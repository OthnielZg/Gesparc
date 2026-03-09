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
        // 1. Mettre à jour les rôles dans la table users
        DB::table('users')->where('role', 'SDREVT')->update([
            'role' => 'SDGRTE',
            'email' => 'sdgrte@dgi.ci'
        ]);

        // 2. Mettre à jour les statuts et décisions dans la table demandes
        DB::table('demandes')->where('statut', 'proposition_sdrevt')->update(['statut' => 'proposition_sdgrte']);
        DB::table('demandes')->where('decision_dsi', 'retient_sdrevt')->update(['decision_dsi' => 'retient_sdgrte']);

        // 3. Renommer les colonnes dans la table demandes
        Schema::table('demandes', function (Blueprint $table) {
            $table->renameColumn('decision_sdrevt', 'decision_sdgrte');
            $table->renameColumn('justification_sdrevt', 'justification_sdgrte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->renameColumn('decision_sdgrte', 'decision_sdrevt');
            $table->renameColumn('justification_sdgrte', 'justification_sdrevt');
        });

        DB::table('demandes')->where('decision_dsi', 'retient_sdgrte')->update(['decision_dsi' => 'retient_sdrevt']);
        DB::table('demandes')->where('statut', 'proposition_sdgrte')->update(['statut' => 'proposition_sdrevt']);

        DB::table('users')->where('role', 'SDGRTE')->update([
            'role' => 'SDREVT',
            'email' => 'sdrevt@dgi.ci'
        ]);
    }
};
