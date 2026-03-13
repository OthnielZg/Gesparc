<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ville;
use App\Models\Direction;
use App\Models\Service;
use App\Models\Bureau;
use App\Models\Materiel;
use App\Models\Demande;
use App\Models\Fournisseur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // ==========================================
        // 1. ORGANISATION (HIÉRARCHIE DGI)
        // ==========================================

        // VILLES
        $abidjan = Ville::create(['nom_ville' => 'Abidjan']);
        $bouake = Ville::create(['nom_ville' => 'Bouaké']);
        $sanpedro = Ville::create(['nom_ville' => 'San Pédro']);

        // DIRECTIONS - ABIDJAN
        $drAbidjanSud = Direction::create(['nom_direction' => 'DR Abidjan Sud', 'id_ville' => $abidjan->id]);
        $drAbidjanNord = Direction::create(['nom_direction' => 'DR Abidjan Nord', 'id_ville' => $abidjan->id]);
        $directionCentrale = Direction::create(['nom_direction' => 'Direction Centrale (DSI/SDGRTE)', 'id_ville' => $abidjan->id]);

        // DIRECTIONS - BOUAKÉ & SAN PÉDRO
        $drBouake = Direction::create(['nom_direction' => 'DR Bouaké', 'id_ville' => $bouake->id]);
        $drSanPedro = Direction::create(['nom_direction' => 'DR San Pédro', 'id_ville' => $sanpedro->id]);

        // SERVICES - ABIDJAN SUD
        $cdiMarcory = Service::create(['nom_service' => 'CDI Marcory', 'id_direction' => $drAbidjanSud->id]);
        $cdiTreichville = Service::create(['nom_service' => 'CDI Treichville', 'id_direction' => $drAbidjanSud->id]);

        // SERVICES - ABIDJAN NORD
        $cdiCocody = Service::create(['nom_service' => 'CDI Cocody', 'id_direction' => $drAbidjanNord->id]);
        $cdiYopougon = Service::create(['nom_service' => 'CDI Yopougon', 'id_direction' => $drAbidjanNord->id]);

        // SERVICES - BOUAKÉ
        $cdiBouakeNord = Service::create(['nom_service' => 'CDI Bouaké Nord', 'id_direction' => $drBouake->id]);

        // SERVICES - CENTRAUX
        $serviceDev = Service::create(['nom_service' => 'Développement & Projets', 'id_direction' => $directionCentrale->id]);
        $serviceSystemes = Service::create(['nom_service' => 'Systèmes et Réseaux', 'id_direction' => $directionCentrale->id]);


        // BUREAUX
        // --- Marcory
        $burMarcoryRecette = Bureau::create(['nom_bureau' => 'Recette Marcory', 'id_service' => $cdiMarcory->id]);
        $burMarcoryAssiette = Bureau::create(['nom_bureau' => 'Assiette Marcory', 'id_service' => $cdiMarcory->id]);
        
        // --- Treichville
        $burTreichvilleRecette = Bureau::create(['nom_bureau' => 'Recette Treichville', 'id_service' => $cdiTreichville->id]);
        
        // --- Cocody
        $burCocodyRecette = Bureau::create(['nom_bureau' => 'Recette Cocody', 'id_service' => $cdiCocody->id]);
        
        // --- Bouaké
        $burBouake1 = Bureau::create(['nom_bureau' => 'Bureau Accueil Bouaké', 'id_service' => $cdiBouakeNord->id]);

        // --- Centraux
        $burDevWeb = Bureau::create(['nom_bureau' => 'Dev Web', 'id_service' => $serviceDev->id]);


        // ==========================================
        // 2. UTILISATEURS (Toutes les strates)
        // ==========================================

        // --- ADMINS / GESTIONNAIRES CENTRAUX ---
        User::create([
            'prenom' => 'Admin', 'nom' => 'System', 'email' => 'admin@dgi.ci',
            'password' => $password, 'role' => User::ROLE_ADMIN, 'actif' => true,
        ]);

        User::create([
            'prenom' => 'Chef', 'nom' => 'SEDMI', 'email' => 'gestionnaire@dgi.ci',
            'password' => $password, 'role' => User::ROLE_GESTIONNAIRE, 'id_direction' => $directionCentrale->id, 'actif' => true,
        ]);

        User::create([
            'prenom' => 'Chef', 'nom' => 'SDGRTE', 'email' => 'sdgrte@dgi.ci',
            'password' => $password, 'role' => User::ROLE_SDGRTE, 'id_direction' => $directionCentrale->id, 'actif' => true,
        ]);

        User::create([
            'prenom' => 'Directeur', 'nom' => 'DSI', 'email' => 'dsi@dgi.ci',
            'password' => $password, 'role' => User::ROLE_DSI, 'id_direction' => $directionCentrale->id, 'actif' => true,
        ]);

        // --- DIRECTEURS RÉGIONAUX (DR) ---
        $userDrSud = User::create([
            'prenom' => 'DR', 'nom' => 'Abidjan Sud', 'email' => 'dr.sud@dgi.ci',
            'password' => $password, 'role' => User::ROLE_DR, 'id_direction' => $drAbidjanSud->id, 'actif' => true,
        ]);

        $userDrNord = User::create([
            'prenom' => 'DR', 'nom' => 'Abidjan Nord', 'email' => 'dr.nord@dgi.ci',
            'password' => $password, 'role' => User::ROLE_DR, 'id_direction' => $drAbidjanNord->id, 'actif' => true,
        ]);

        $userDrBouake = User::create([
            'prenom' => 'DR', 'nom' => 'Bouaké', 'email' => 'dr.bouake@dgi.ci',
            'password' => $password, 'role' => User::ROLE_DR, 'id_direction' => $drBouake->id, 'actif' => true,
        ]);

        $userDrSanPedro = User::create([
            'prenom' => 'DR', 'nom' => 'San Pédro', 'email' => 'dr.sanpedro@dgi.ci',
            'password' => $password, 'role' => User::ROLE_DR, 'id_direction' => $drSanPedro->id, 'actif' => true,
        ]);

        // --- CHEFS CDI ---
        $userChefMarcory = User::create([
            'prenom' => 'Chef', 'nom' => 'CDI Marcory', 'email' => 'cdi.marcory@dgi.ci',
            'password' => $password, 'role' => User::ROLE_CHEF_CDI, 'id_bureau' => $burMarcoryRecette->id, 'actif' => true,
        ]);

        $userChefTreichville = User::create([
            'prenom' => 'Chef', 'nom' => 'CDI Treichville', 'email' => 'cdi.treichville@dgi.ci',
            'password' => $password, 'role' => User::ROLE_CHEF_CDI, 'id_bureau' => $burTreichvilleRecette->id, 'actif' => true,
        ]);

        $userChefCocody = User::create([
            'prenom' => 'Chef', 'nom' => 'CDI Cocody', 'email' => 'cdi.cocody@dgi.ci',
            'password' => $password, 'role' => User::ROLE_CHEF_CDI, 'id_bureau' => $burCocodyRecette->id, 'actif' => true,
        ]);

        // --- AGENTS ---
        // Marcory (Abidjan Sud)
        $agentMarcory1 = User::create(['prenom' => 'Agent 1', 'nom' => 'Marcory (Recette)', 'email' => 'agent1.marcory@dgi.ci', 'password' => $password, 'role' => User::ROLE_AGENT, 'id_bureau' => $burMarcoryRecette->id, 'actif' => true]);
        $agentMarcory2 = User::create(['prenom' => 'Agent 2', 'nom' => 'Marcory (Assiette)', 'email' => 'agent2.marcory@dgi.ci', 'password' => $password, 'role' => User::ROLE_AGENT, 'id_bureau' => $burMarcoryAssiette->id, 'actif' => true]);
        
        // Treichville (Abidjan Sud)
        $agentTreichville = User::create(['prenom' => 'Agent 1', 'nom' => 'Treichville', 'email' => 'agent.treichville@dgi.ci', 'password' => $password, 'role' => User::ROLE_AGENT, 'id_bureau' => $burTreichvilleRecette->id, 'actif' => true]);

        // Cocody (Abidjan Nord)
        $agentCocody = User::create(['prenom' => 'Agent 1', 'nom' => 'Cocody', 'email' => 'agent.cocody@dgi.ci', 'password' => $password, 'role' => User::ROLE_AGENT, 'id_bureau' => $burCocodyRecette->id, 'actif' => true]);

        // Bouaké
        $agentBouake = User::create(['prenom' => 'Agent 1', 'nom' => 'Bouaké', 'email' => 'agent.bouake@dgi.ci', 'password' => $password, 'role' => User::ROLE_AGENT, 'id_bureau' => $burBouake1->id, 'actif' => true]);


        // ==========================================
        // 3. MATÉRIELS ET FOURNISSEURS
        // ==========================================
        $fournisseurHP = Fournisseur::create(['nom_fournisseur' => 'HP Côte d\'Ivoire', 'email' => 'contact@hp.ci', 'telephone' => '2720202020']);
        $fournisseurDell = Fournisseur::create(['nom_fournisseur' => 'Dell Partner', 'email' => 'contact@dell.ci', 'telephone' => '2721212121']);

        // Matériels Disponibles
        for($i=1; $i<=5; $i++) {
            Materiel::create([
                'categorie' => 'Ordinateur Bureau', 'marque' => 'HP', 'modele' => 'ProDesk 400', 
                'numero_serie' => 'HPFixe00'.$i, 'statut' => 'disponible', 
                'id_fournisseur' => $fournisseurHP->id, 'prix_acquisition' => 450000.00
            ]);
        }
        for($i=1; $i<=3; $i++) {
            Materiel::create([
                'categorie' => 'Imprimante', 'marque' => 'HP', 'modele' => 'LaserJet Pro', 
                'numero_serie' => 'HPImp00'.$i, 'statut' => 'disponible', 
                'id_fournisseur' => $fournisseurHP->id, 'prix_acquisition' => 125000.00
            ]);
        }
        for($i=1; $i<=4; $i++) {
            Materiel::create([
                'categorie' => 'Ordinateur Portable', 'marque' => 'Dell', 'modele' => 'Latitude 5000', 
                'numero_serie' => 'DellPort00'.$i, 'statut' => 'disponible', 
                'id_fournisseur' => $fournisseurDell->id, 'prix_acquisition' => 650000.00
            ]);
        }


        // ==========================================
        // 4. DEMANDES TEST (États variés pour vérifier scopes)
        // ==========================================

        // --- Demande 1: Agent Marcory -> Pré-demande (Visible: Lui, Chef CDI Marcory)
        Demande::create([
            'reference' => Demande::generateReference(),
            'id_utilisateur' => $agentMarcory1->id,
            'categorie_demandee' => 'Ordinateur Bureau',
            'quantite' => 1,
            'motif_demande' => 'Nouvel agent, besoin de poste de travail.',
            'priorite' => 'normale',
            'statut' => 'pre_demande',
        ]);

        // --- Demande 2: Agent Marcory 2 -> En attente DR (Visible: Lui, Chef CDI Marcory, DR Sud, Gestionnaire/DSI/Admin)
        Demande::create([
            'reference' => Demande::generateReference(),
            'id_utilisateur' => $agentMarcory2->id,
            'id_chef_cdi' => $userChefMarcory->id,
            'categorie_demandee' => 'Imprimante',
            'quantite' => 1,
            'motif_demande' => 'Imprimante bureau en panne.',
            'priorite' => 'haute',
            'statut' => 'en_attente_dr',
        ]);

        // --- Demande 3: Chef Treichville -> En attente DR (Visible: Lui, DR Sud, ...)
        Demande::create([
            'reference' => Demande::generateReference(),
            'id_utilisateur' => $userChefTreichville->id,
            'id_chef_cdi' => $userChefTreichville->id,
            'categorie_demandee' => 'Ordinateur Portable',
            'quantite' => 1,
            'motif_demande' => 'Besoin équipement directionnel.',
            'priorite' => 'normale',
            'statut' => 'en_attente_dr',
        ]);

        // --- Demande 4: Agent Cocody -> Transmise Gestionnaire (Visible: Lui, Chef CDI Cocody, DR Nord, Gestionnaire)
        Demande::create([
            'reference' => Demande::generateReference(),
            'id_utilisateur' => $agentCocody->id,
            'id_chef_cdi' => $userChefCocody->id,
            'categorie_demandee' => 'Ordinateur Bureau',
            'quantite' => 2,
            'motif_demande' => 'Renouvellement parc vieillissant.',
            'priorite' => 'normale',
            'statut' => 'transmise_gestionnaire',
        ]);

        // --- Demande 5: Agent Bouaké -> Rejetée par Chef CDI
        Demande::create([
            'reference' => Demande::generateReference(),
            'id_utilisateur' => $agentBouake->id,
            'id_chef_cdi' => null,
            'categorie_demandee' => 'Écran',
            'quantite' => 1,
            'motif_demande' => 'Ecran secondaire',
            'priorite' => 'basse',
            'statut' => 'rejetee',
        ]);
        
        // --- Demande 6: DR Sud -> Directement transmise gestionnaire
        Demande::create([
            'reference' => Demande::generateReference(),
            'id_utilisateur' => $userDrSud->id,
            'categorie_demandee' => 'Ordinateur Portable',
            'quantite' => 1,
            'motif_demande' => 'Equipement DR',
            'priorite' => 'urgente',
            'statut' => 'transmise_gestionnaire',
        ]);

    }
}
