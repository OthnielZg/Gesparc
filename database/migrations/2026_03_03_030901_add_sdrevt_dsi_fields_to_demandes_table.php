<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            // ── Étape 5 : Champs SDREVT ──────────────────────────────────
            // Décision du SDREVT : valide la proposition SEDMI ou contre-propose
            $table->string('decision_sdrevt', 50)
                ->nullable()
                ->after('materiel_propose');

            // Matériel alternatif proposé par le SDREVT (id ou texte libre)
            $table->string('materiel_contre_propose', 255)
                ->nullable()
                ->after('decision_sdrevt');

            // Justification de la contre-proposition
            $table->text('justification_sdrevt')
                ->nullable()
                ->after('materiel_contre_propose');

            // Référence/Modèle alternatif saisi par le SDREVT
            $table->string('reference_contre_propose', 255)
                ->nullable()
                ->after('justification_sdrevt');

            // ── Étape 6 : Champs DSI ─────────────────────────────────────
            // Choix final du DSI
            $table->string('decision_dsi', 50)
                ->nullable()
                ->after('reference_contre_propose');

            // Matériel finalement retenu par le DSI (peut être une 3e option)
            $table->string('materiel_retenu_dsi', 255)
                ->nullable()
                ->after('decision_dsi');

            // Note de décision du DSI (visible dans l'historique par tous)
            $table->text('commentaire_dsi')
                ->nullable()
                ->after('materiel_retenu_dsi');
        });
    }

    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn([
                'decision_sdrevt',
                'materiel_contre_propose',
                'justification_sdrevt',
                'reference_contre_propose',
                'decision_dsi',
                'materiel_retenu_dsi',
                'commentaire_dsi',
            ]);
        });
    }
};
