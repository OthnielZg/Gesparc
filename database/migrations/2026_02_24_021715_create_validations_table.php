<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_demande')->constrained('demandes')->cascadeOnDelete();
            $table->foreignId('id_utilisateur')->constrained('users')->cascadeOnDelete();
            $table->string('decision', 50);
            $table->string('niveau_validation', 100);
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validations');
    }
};
