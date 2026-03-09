<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ville;
use App\Models\Direction;
use App\Models\Service;
use App\Models\Bureau;
use App\Models\Fournisseur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Villes
        $plateau = Ville::create(['nom_ville' => 'Plateau']);
        $cocody = Ville::create(['nom_ville' => 'Cocody']);

        // 2. Directions
        $dg = Direction::create(['nom_direction' => 'Direction Générale', 'id_ville' => $plateau->id]);
        $dsi = Direction::create(['nom_direction' => 'Direction des Systèmes d\'Information', 'id_ville' => $plateau->id]);

        // 3. Services
        $dev = Service::create(['nom_service' => 'Développement', 'id_direction' => $dsi->id]);
        $support = Service::create(['nom_service' => 'Support & Maintenance', 'id_direction' => $dsi->id]);

        // 4. Bureaux
        $bureauDev1 = Bureau::create(['nom_bureau' => 'Bureau Dev 1', 'id_service' => $dev->id]);
        $bureauSupport1 = Bureau::create(['nom_bureau' => 'Bureau Support 1', 'id_service' => $support->id]);

        // 5. Fournisseurs
        Fournisseur::create([
            'nom_fournisseur' => 'CFAO Ivoirienne',
            'email' => 'contact@cfao.ci',
            'telephone' => '2721212121',
            'adresse' => 'Treichville, Abidjan'
        ]);

        // 6. Users (Admin creates them)
        // Password for all: password
        $password = Hash::make('password');

        // Admin
        User::create([
            'prenom' => 'Admin',
            'nom' => 'System',
            'email' => 'admin@dgi.ci',
            'password' => $password,
            'role' => User::ROLE_ADMIN,
            'actif' => true,
        ]);

        // Chef CDI
        User::create([
            'prenom' => 'Marc',
            'nom' => 'Koffi',
            'email' => 'chef.cdi@dgi.ci',
            'password' => $password,
            'role' => User::ROLE_CHEF_CDI,
            'actif' => true,
        ]);

        // DR
        User::create([
            'prenom' => 'Sarah',
            'nom' => 'Koné',
            'email' => 'dr@dgi.ci',
            'password' => $password,
            'role' => User::ROLE_DR,
            'actif' => true,
        ]);

        // Gestionnaire (Chef SEDMI)
        User::create([
            'prenom' => 'Issa',
            'nom' => 'Traoré',
            'email' => 'gestionnaire@dgi.ci',
            'password' => $password,
            'role' => User::ROLE_GESTIONNAIRE,
            'actif' => true,
        ]);

        // SDGRTE
        User::create([
            'prenom' => 'Paul',
            'nom' => 'Aman',
            'email' => 'sdgrte@dgi.ci',
            'password' => $password,
            'role' => User::ROLE_SDGRTE,
            'actif' => true,
        ]);

        // DSI
        User::create([
            'prenom' => 'Jean',
            'nom' => 'Diallo',
            'email' => 'dsi@dgi.ci',
            'password' => $password,
            'role' => User::ROLE_DSI,
            'actif' => true,
        ]);

        // Agent
        User::create([
            'prenom' => 'Moussa',
            'nom' => 'Diabaté',
            'email' => 'agent@dgi.ci',
            'password' => $password,
            'role' => User::ROLE_AGENT,
            'id_bureau' => $bureauDev1->id,
            'actif' => true,
        ]);
    }
}
