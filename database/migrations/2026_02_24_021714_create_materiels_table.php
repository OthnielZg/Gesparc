<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->string('numero_serie', 100)->unique();
            $table->string('categorie', 100);
            $table->string('marque', 100);
            $table->string('modele', 100);
            $table->enum('statut', ['disponible', 'affecte', 'maintenance', 'hors_service'])->default('disponible');
            $table->decimal('prix_acquisition', 12, 2)->nullable();
            $table->foreignId('id_fournisseur')->nullable()->constrained('fournisseurs')->nullOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};
