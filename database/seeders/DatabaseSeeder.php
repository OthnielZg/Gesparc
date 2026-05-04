<?php

namespace Database\Seeders;

use App\Models\Bureau;
use App\Models\Direction;
use App\Models\Service;
use App\Models\User;
use App\Models\Ville;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Fournisseur;
use App\Models\Materiel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // ==========================================
        // 0. RESET PRÉALABLE (Oracle Safe)
        // ==========================================
        echo "Nettoyage des données existantes...\n";
        
        // Liste des tables dans l'ordre de suppression (clés étrangères d'abord)
        $tables = ['affectations', 'validations', 'demandes', 'materiels', 'fournisseurs', 'bureaux', 'services', 'directions', 'villes', 'users'];
        
        foreach ($tables as $table) {
            try {
                DB::table($table)->delete();
                echo "✅ $table vidée.\n";
            } catch (\Exception $e) {
                // Table peut ne pas exister à ce stade
            }
        }

        // ==========================================
        // 1. ORGANISATION (HIÉRARCHIE DGI)
        // ==========================================

        // VILLES
        $abidjan = Ville::create(['nom_ville' => 'Abidjan']);
        $yakro = Ville::create(['nom_ville' => 'Yamoussoukro']);

        // DIRECTIONS
        // On sépare la DSI des Directions Régionales, mais toutes sont à Abidjan
        $directionDsi = Direction::create(['nom_direction' => 'Direction des Systèmes Informatiques (DSI)', 'id_ville' => $abidjan->id]);
        $drAbidjan = Direction::create(['nom_direction' => "Direction Régionale d'Abidjan (Territorial)", 'id_ville' => $abidjan->id]);
        $drYakro = Direction::create(['nom_direction' => 'DR Yamoussoukro (Territorial)', 'id_ville' => $yakro->id]);
        $dirCalculYakro = Direction::create(['nom_direction' => 'Direction du Centre de Calcul (Yakro)', 'id_ville' => $yakro->id]);
        
        // --- Bouaké (Ville de l'intérieur)
        $villeBouake = Ville::create(['nom_ville' => 'Bouaké']);
        $drBouake = Direction::create(['nom_direction' => 'DR Bouaké', 'id_ville' => $villeBouake->id]);

        // SERVICES (Multiples par Direction)
        // --- DSI (Direction Centrale)
        $serviceSedmi = Service::create(['nom_service' => 'Service SEDMI (Gestion Parc)', 'id_direction' => $directionDsi->id]);
        $serviceSdgrte = Service::create(['nom_service' => 'Service SDGRTE (Tech)', 'id_direction' => $directionDsi->id]);
        $serviceDevDsi = Service::create(['nom_service' => 'Service Développement DSI', 'id_direction' => $directionDsi->id]);
        
        // --- Abidjan Territorial (Sous le DR Abidjan)
        $cdiCocody = Service::create(['nom_service' => 'CDI Cocody', 'id_direction' => $drAbidjan->id]);
        $cdiPlateau = Service::create(['nom_service' => 'CDI Plateau', 'id_direction' => $drAbidjan->id]);

        // --- Yamoussoukro
        $cdiYakro = Service::create(['nom_service' => 'CDI Yamoussoukro', 'id_direction' => $drYakro->id]);
        $serviceCalcul = Service::create(['nom_service' => 'Service Exploitation Calcul', 'id_direction' => $dirCalculYakro->id]);

        // --- Bouaké
        $cdiBouake = Service::create(['nom_service' => 'CDI Bouaké', 'id_direction' => $drBouake->id]);

        // BUREAUX
        $burSedmi = Bureau::create(['nom_bureau' => 'Bureau Gestion Parc', 'id_service' => $serviceSedmi->id]);
        $burSdgrte = Bureau::create(['nom_bureau' => 'Bureau Etudes Tech', 'id_service' => $serviceSdgrte->id]);
        $burDevDsi = Bureau::create(['nom_bureau' => 'Pôle Web', 'id_service' => $serviceDevDsi->id]);
        
        $burCocodyCDI = Bureau::create(['nom_bureau' => 'Bureau CDI Cocody', 'id_service' => $cdiCocody->id]);
        $burPlateauCDI = Bureau::create(['nom_bureau' => 'Bureau CDI Plateau', 'id_service' => $cdiPlateau->id]);

        $burYakroCDI = Bureau::create(['nom_bureau' => 'Bureau CDI Yakro', 'id_service' => $cdiYakro->id]);
        $burCalcul = Bureau::create(['nom_bureau' => 'Bureau Serveurs Yakro', 'id_service' => $serviceCalcul->id]);
        $burBouakeCDI = Bureau::create(['nom_bureau' => 'Bureau CDI Bouaké', 'id_service' => $cdiBouake->id]);

        // ==========================================
        // 2. UTILISATEURS
        // ==========================================

        // --- ADMIN ---
        User::create(['prenom' => 'Admin', 'nom' => 'System', 'email' => 'admin@dgi.ci', 'password' => $password, 'role' => User::ROLE_ADMIN, 'actif' => true]);

        // --- ACTEURS CENTRAUX (Rattachés à la Direction DSI) ---
        User::create([
            'prenom' => 'Chef', 'nom' => 'SEDMI (Gestionnaire)', 'email' => 'gestionnaire@dgi.ci',
            'password' => $password, 'role' => User::ROLE_GESTIONNAIRE, 'id_direction' => $directionDsi->id, 'id_bureau' => $burSedmi->id, 'actif' => true,
        ]);

        User::create([
            'prenom' => 'Chef', 'nom' => 'SDGRTE', 'email' => 'sdgrte@dgi.ci',
            'password' => $password, 'role' => User::ROLE_SDGRTE, 'id_direction' => $directionDsi->id, 'id_bureau' => $burSdgrte->id, 'actif' => true,
        ]);

        User::create([
            'prenom' => 'Directeur', 'nom' => 'DSI', 'email' => 'dsi@dgi.ci',
            'password' => $password, 'role' => User::ROLE_DSI, 'id_direction' => $directionDsi->id, 'actif' => true,
        ]);

        // --- DIRECTEURS RÉGIONAUX (DR) ---
        // Le DR Abidjan verra TOUT ce qui est à Abidjan (DSI + Territorial)
        User::create(['prenom' => 'Directeur', 'nom' => 'Régional Abidjan', 'email' => 'dr.abidjan@dgi.ci', 'password' => $password, 'role' => User::ROLE_DR, 'id_direction' => $drAbidjan->id, 'actif' => true]);
        
        User::create(['prenom' => 'Directeur', 'nom' => 'Régional Yakro', 'email' => 'dr.yakro@dgi.ci', 'password' => $password, 'role' => User::ROLE_DR, 'id_direction' => $drYakro->id, 'actif' => true]);
        User::create(['prenom' => 'Directeur', 'nom' => 'Régional Bouaké', 'email' => 'dr.bouake@dgi.ci', 'password' => $password, 'role' => User::ROLE_DR, 'id_direction' => $drBouake->id, 'actif' => true]);

        // --- CHEFS DE SERVICE (Niveau Local V1) ---
        // Abidjan DSI
        User::create(['prenom' => 'Chef', 'nom' => 'Dev DSI', 'email' => 'chef.dev@dgi.ci', 'password' => $password, 'role' => User::ROLE_CHEF_CDI, 'id_bureau' => $burDevDsi->id, 'actif' => true]);
        
        // Abidjan Territorial
        User::create(['prenom' => 'Chef', 'nom' => 'CDI Cocody', 'email' => 'chef.cocody@dgi.ci', 'password' => $password, 'role' => User::ROLE_CHEF_CDI, 'id_bureau' => $burCocodyCDI->id, 'actif' => true]);
        User::create(['prenom' => 'Chef', 'nom' => 'CDI Plateau', 'email' => 'chef.plateau@dgi.ci', 'password' => $password, 'role' => User::ROLE_CHEF_CDI, 'id_bureau' => $burPlateauCDI->id, 'actif' => true]);

        // Intérieur
        User::create(['prenom' => 'Chef', 'nom' => 'CDI Yakro', 'email' => 'chef.yakro@dgi.ci', 'password' => $password, 'role' => User::ROLE_CHEF_CDI, 'id_bureau' => $burYakroCDI->id, 'actif' => true]);
        User::create(['prenom' => 'Chef', 'nom' => 'Calcul Yakro', 'email' => 'chef.calcul@dgi.ci', 'password' => $password, 'role' => User::ROLE_CHEF_CDI, 'id_bureau' => $burCalcul->id, 'actif' => true]);
        User::create(['prenom' => 'Chef', 'nom' => 'CDI Bouaké', 'email' => 'chef.bouake@dgi.ci', 'password' => $password, 'role' => User::ROLE_CHEF_CDI, 'id_bureau' => $burBouakeCDI->id, 'actif' => true]);

        // --- AGENTS (Origine des demandes) ---
        User::create(['prenom' => 'Agent', 'nom' => 'Dev DSI', 'email' => 'agent.dev@dgi.ci', 'password' => $password, 'role' => User::ROLE_AGENT, 'id_bureau' => $burDevDsi->id, 'actif' => true]);
        User::create(['prenom' => 'Agent', 'nom' => 'CDI Plateau', 'email' => 'agent.plateau@dgi.ci', 'password' => $password, 'role' => User::ROLE_AGENT, 'id_bureau' => $burPlateauCDI->id, 'actif' => true]);
        User::create(['prenom' => 'Agent', 'nom' => 'Calcul Yakro', 'email' => 'agent.calcul@dgi.ci', 'password' => $password, 'role' => User::ROLE_AGENT, 'id_bureau' => $burCalcul->id, 'actif' => true]);
        User::create(['prenom' => 'Agent', 'nom' => 'CDI Bouaké', 'email' => 'agent.bouake@dgi.ci', 'password' => $password, 'role' => User::ROLE_AGENT, 'id_bureau' => $burBouakeCDI->id, 'actif' => true]);

        // ==========================================
        // 3. MATÉRIEL DE TEST
        // ==========================================
        $fournisseur = Fournisseur::create(['nom_fournisseur' => 'Oracle France', 'email' => 'support@oracle.com']);
        
        for($i=1; $i<=5; $i++) {
            Materiel::create([
                'categorie' => 'Ordinateur', 'marque' => 'HP', 'modele' => 'EliteDesk 800', 
                'numero_serie' => 'SN-ABIDJAN-00'.$i, 'statut' => 'disponible', 
                'id_fournisseur' => $fournisseur->id, 'prix_acquisition' => 500000
            ]);
        }
    }
}
