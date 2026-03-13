<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandes', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // REF-YYYYMMDD-XXXX
            $table->integer('quantite')->default(1);
            $table->string('categorie_demandee', 100);
            $table->text('motif_demande');
            $table->enum('priorite', ['basse', 'normale', 'haute', 'urgente'])->default('normale');
            $table->enum('statut', [
                'pre_demande',         
                'en_attente_dr',       
                'transmise_gestionnaire', 
                'proposition_sdrevt',  
                'en_attente_dsi',      
                'validee',             
                'rejetee',             
                'annulee',             
            ])->default('pre_demande');
            $table->foreignId('id_utilisateur')->constrained('users')->cascadeOnDelete(); 
            $table->foreignId('id_chef_cdi')->nullable()->constrained('users')->nullOnDelete(); 
            $table->text('commentaire_gestionnaire')->nullable();
            $table->string('materiel_propose', 255)->nullable(); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
