<?php

namespace Database\Seeders;

use App\Models\Demande;
use App\Models\Direction;
use App\Models\Service;
use App\Models\User;
use App\Models\Ville;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkflowSimulationSeeder extends Seeder
{
    public function run(): void
    {
        echo "🚀 Simulation de l'activité régionale en cours...\n";

        // 1. Récupération des bases
        $abidjan = Ville::where('nom_ville', 'Abidjan')->first();
        if (!$abidjan) return;

        // 2. Création de Directions supplémentaires pour "remplir" le Radar du DR
        $directions = [
            ['nom' => 'Direction Plateau II', 'color' => 'unhealthy', 'count' => 12],
            ['nom' => 'Direction Adjamé', 'color' => 'needs_action', 'count' => 3],
            ['nom' => 'Direction Treichville', 'color' => 'healthy', 'count' => 0],
            ['nom' => 'Direction Koumassi', 'color' => 'needs_action', 'count' => 2],
        ];

        foreach ($directions as $dirData) {
            $direction = Direction::firstOrCreate(
                ['nom_direction' => $dirData['nom']],
                ['id_ville' => $abidjan->id]
            );

            // Créer un utilisateur "Agent" pour cette direction pour pouvoir faire des demandes
            $agent = User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $dirData['nom'])) . '@dgi.ci'],
                [
                    'prenom' => 'Agent',
                    'nom' => str_replace('Direction ', '', $dirData['nom']),
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'role' => 'agent',
                    'id_direction' => $direction->id,
                    'actif' => true
                ]
            );

            // Créer les demandes correspondantes au statut voulu
            for ($i = 0; $i < $dirData['count']; $i++) {
                Demande::create([
                    'reference' => 'REF-' . now()->format('YmdHis') . '-' . $direction->id . '-' . $i,
                    'quantite' => 1,
                    'categorie_demandee' => collect(['Ordinateur', 'Imprimante', 'Scanner'])->random(),
                    'motif_demande' => 'Renouvellement de matériel défectueux',
                    'priorite' => collect(['haute', 'normale'])->random(),
                    'statut' => 'en_attente_dr',
                    'id_utilisateur' => $agent->id,
                    'created_at' => now()->subMinutes(rand(5, 500))
                ]);
            }

            // Ajouter quelques demandes déjà traitées pour les graphiques
            for ($i = 0; $i < 5; $i++) {
                Demande::create([
                    'reference' => 'HIST-' . now()->format('YmdHis') . '-' . $direction->id . '-' . $i,
                    'quantite' => 1,
                    'categorie_demandee' => 'Ordinateur',
                    'motif_demande' => 'Dotation initiale',
                    'statut' => collect(['validee', 'finalisee', 'rejetee'])->random(),
                    'id_utilisateur' => $agent->id,
                    'created_at' => now()->subDays(rand(1, 10))
                ]);
            }
        }

        echo "✅ Radar et Flux d'activité peuplés avec succès.\n";
    }
}
