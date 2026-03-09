<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Materiel;
use App\Models\Demande;
use App\Models\Fournisseur;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fournisseur = Fournisseur::first() ?? Fournisseur::factory()->create();
        $agent = User::where('role', 'agent')->first() ?? User::factory()->agent()->create();

        // 1. Matériels disponibles pour tests QR Code et Inventaire
        Materiel::factory()->count(10)->create([
            'id_fournisseur' => $fournisseur->id,
            'statut' => 'disponible',
        ]);

        // 2. Matériels déjà affectés
        Materiel::factory()->count(5)->create([
            'id_fournisseur' => $fournisseur->id,
            'statut' => 'affecte',
        ]);

        // 3. Demandes à différents stades
        
        // Dossier chez le Gestionnaire (Etape 3)
        Demande::factory()->count(3)->create([
            'id_utilisateur' => $agent->id,
            'statut' => 'transmise_gestionnaire',
            'priorite' => 'normale',
        ]);

        // Dossiers validés par DSI (Prêts pour finalisation / QR Code)
        $materielsDsi = Materiel::factory()->count(3)->create([
            'statut' => 'disponible',
            'marque' => 'Dell',
            'modele' => 'Latitude 5420',
        ]);

        foreach ($materielsDsi as $mat) {
            Demande::factory()->create([
                'id_utilisateur' => $agent->id,
                'statut' => 'validee_dsi',
                'decision_dsi' => 'autre',
                'materiel_retenu_dsi' => "Dell Latitude 5420 ({$mat->numero_serie})",
                'commentaire_dsi' => 'Matériel prêt pour remise physique.',
            ]);
        }

        // Dossiers déjà finalisés
        Demande::factory()->count(2)->create([
            'id_utilisateur' => $agent->id,
            'statut' => 'finalisee',
        ]);
    }
}
