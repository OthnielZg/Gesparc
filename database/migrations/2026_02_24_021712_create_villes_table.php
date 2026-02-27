<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('villes', function (Blueprint $table) {
            $table->id();
            $table->string('nom_ville', 100);
            $table->timestamps();
        });

        Schema::create('directions', function (Blueprint $table) {
            $table->id();
            $table->string('nom_direction', 100);
            $table->foreignId('id_ville')->nullable()->constrained('villes')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('nom_service', 100);
            $table->foreignId('id_direction')->constrained('directions')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('bureaux', function (Blueprint $table) {
            $table->id();
            $table->string('nom_bureau', 100);
            $table->foreignId('id_service')->constrained('services')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bureaux');
        Schema::dropIfExists('services');
        Schema::dropIfExists('directions');
        Schema::dropIfExists('villes');
    }
};
