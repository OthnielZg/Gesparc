# 🗄️ Hyper Deep Dive : La Base de Données (Gesparc)

Ce guide vous explique comment vos données sont stockées et organisées. C'est le "disque dur" de votre application.

---

## 🏗️ 1. Le Schéma Global (Relations)
Imaginez que votre base de données est une série de tableaux Excel qui communiquent entre eux.

- **Utilisateurs** ↔ **Bureaux** : Chaque utilisateur est assis dans un bureau.
- **Bureaux** ↔ **Services** : Chaque bureau appartient à un service.
- **Services** ↔ **Directions** : Chaque service appartient à une direction.
- **Utilisateurs** ↔ **Demandes** : Un utilisateur peut créer plusieurs demandes.
- **Demandes** ↔ **Validations** : Une demande reçoit plusieurs avis au cours de son workflow.

---

## 📜 2. Les Migrations : Le Plan de Construction
Fichiers dans : `database/migrations/`

Chaque fichier est une instruction pour créer une table. Analysons `create_demandes_table.php` :

```php
Schema::create('demandes', function (Blueprint $table) {
    // L'identifiant unique (Automatique)
    $table->id();
    
    // Le numéro de référence unique (ex: REF-20260309-001)
    $table->string('reference')->unique();
    
    // LA CLÉ ÉTRANGÈRE : Le lien vers l'utilisateur (Ligne cruciale)
    $table->foreignId('id_utilisateur')->constrained('users')->onDelete('cascade');
    /* 
       TRADUCTION : 
       - On crée un lien avec la table 'users'.
       - 'onDelete(cascade)' signifie que si on supprime l'utilisateur, 
         ses demandes disparaissent aussi pour ne pas laisser d'orphelins.
    */

    $table->integer('quantite')->default(1);
    $table->string('statut'); // L'état actuel (pre_demande, validee, etc.)
    $table->timestamps(); // Crée 'created_at' (date création) et 'updated_at'
});
```

---

## 🌱 3. Les Seeders : Les Données de Test
Fichiers dans : `database/seeders/`

Quand vous commencez un projet, la base est vide. Le **Seeder** sert à la remplir automatiquement avec des tests.

Analysons `DatabaseSeeder.php` :
```php
public function run(): void {
    // Création de l'Administrateur par défaut
    User::factory()->create([
        'nom' => 'Admin',
        'email' => 'admin@dgi.ci',
        'role' => 'admin',
        'password' => Hash::make('password'),
    ]);
}
```
*Action* : C'est ce fichier qui vous permet de vous connecter tout de suite après avoir installé le projet avec la commande `php artisan db:seed`.

---

## 🔬 4. Facteurs (Factories) : Les Usines à Données
Fichiers dans : `database/factories/`

Une "Factory" est une machine qui génère des données bidon (Faker) pour tester votre site.
```php
return [
    'nom' => fake()->lastName(),
    'prenom' => fake()->firstName(),
    'email' => fake()->unique()->safeEmail(),
    'role' => 'agent',
];
```
*Utilité* : Si vous voulez tester votre site avec 1000 agents, vous n'avez pas besoin de les créer à la main. Laravel le fait en 1 seconde grâce à ces usines.

---

## 🛠️ 5. Commandes de Gestion
- `php artisan migrate` : Applique les nouveaux plans de tables.
- `php artisan migrate:rollback` : Annule la dernière création de table (Si vous avez fait une erreur).
- `php artisan db:wipe` : Efface TOUTE la base de données (Attention !).

---
*Fin du guide Database. Sans ces fichiers, Gesparc n'aurait aucun endroit où se souvenir de vos demandes ou de vos matériels.*
