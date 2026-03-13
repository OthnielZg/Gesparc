<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Demande;
use App\Models\Materiel;
use App\Models\Affectation;
use App\Models\Categorie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Assurer l'existence de quelques catégories de base
        $categories = ['Ordinateur', 'Imprimante', 'Scanner', 'Tablette'];
        foreach ($categories as $cat) {
            DB::table('categories')->updateOrInsert(['libelle' => $cat], ['created_at' => now()]);
        }

        // 2. Générer du matériel
        for ($i = 0; $i < 50; $i++) {
            Materiel::create([
                'numero_serie' => 'SN-' . strtoupper(bin2hex(random_bytes(4))),
                'modele' => $categories[array_rand($categories)] . ' - Model ' . rand(1, 10),
                'categorie' => $categories[array_rand($categories)],
                'statut' => 'disponible',
                'id_fournisseur' => 1,
                'date_acquisition' => now()->subMonths(rand(1, 24)),
            ]);
        }

        // 3. Récupérer les utilisateurs clés
        $agent = User::where('role', 'agent')->first();
        $chefCdi = User::where('role', 'chef_cdi')->first();
        $dr = User::where('role', 'DR')->first();

        if ($agent) {
            // Demandes pour l'agent
            $statuts = array_keys(Demande::statutLabel());
            for ($i = 0; $i < 8; $i++) {
                Demande::create([
                    'reference' => 'DEM-' . date('Y') . '-' . rand(1000, 9999),
                    'id_utilisateur' => $agent->id,
                    'type_demande' => 'Prestation',
                    'objet' => 'Besoin de test ' . $i,
                    'description' => 'Description automatique pour le dashboard',
                    'statut' => $statuts[array_rand($statuts)],
                    'priorite' => 1,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }

            // Matériels pour l'agent
            $materiels = Materiel::where('statut', 'disponible')->limit(3)->get();
            foreach ($materiels as $m) {
                Affectation::create([
                    'id_materiel' => $m->id,
                    'id_utilisateur' => $agent->id,
                    'date_affectation' => now()->subMonths(2),
                    'motif' => 'Dotation initiale',
                    'statut' => 'actif'
                ]);
                $m->update(['statut' => 'affecté']);
            }
        }

        // Demandes pour le service du Chef CDI
        $usersInService = User::whereHas('bureau', function($q) use ($chefCdi) {
            $q->where('id_service', $chefCdi->bureau->id_service);
        })->get();

        foreach ($usersInService as $u) {
            for ($i = 0; $i < 3; $i++) {
                Demande::create([
                    'reference' => 'SERV-' . date('Y') . '-' . rand(1000, 9999),
                    'id_utilisateur' => $u->id,
                    'type_demande' => 'Acquisition',
                    'objet' => 'Demande service ' . $i,
                    'description' => 'Test dashboard service',
                    'statut' => array_keys(Demande::statutLabel())[array_rand($statuts)],
                    'priorite' => rand(1, 3),
                    'created_at' => now()->subWeeks(rand(1, 4)),
                ]);
            }
        }
        
        // Alertes de stock pour le gestionnaire
        Materiel::where('categorie', 'Scanner')->limit(45)->update(['statut' => 'affecté']); // Laisser peu de Scanners
    }
}
