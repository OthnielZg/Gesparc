<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_bordereau')->unique(); // BRD-YYYYMMDD-XXXX
            $table->foreignId('id_demande')->constrained('demandes')->cascadeOnDelete();
            $table->foreignId('id_utilisateur')->constrained('users')->cascadeOnDelete(); // Bénéficiaire
            $table->foreignId('id_materiel')->constrained('materiels')->cascadeOnDelete();
            $table->enum('etat_reception', ['neuf', 'bon', 'usage', 'degrade'])->default('neuf');
            $table->enum('etat_retour', ['bon', 'usage', 'degrade', 'perdu'])->nullable();
            $table->timestamp('date_affectation')->useCurrent();
            $table->timestamp('date_retour')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};
