# GESPARC - Gestion du Parc Informatique DGI

**GESPARC** est une application web moderne développée pour la **Direction Générale des Impôts (DGI)** de la République de Côte d'Ivoire. Elle permet de gérer l'inventaire du matériel informatique et d'automatiser le processus de demande et d'affectation via un workflow structuré (Cycle MCT).

## 🚀 Fonctionnalités Clés

- **MCT Workflow (7 étapes)** : Un processus rigoureux allant de la création de la pré-demande par l'agent jusqu'à l'approbation finale par la DSI.
- **Gestion d'Inventaire** : Suivi précis du matériel (ordinateurs, imprimantes, etc.) avec gestion des statuts et des fournisseurs.
- **Interface DGI Premium** : Design soigné respectant la charte graphique de la DGI (Orange & Vert).
- **Redirection par Rôles** : Accès sécurisé et tableaux de bord personnalisés pour 7 types d'utilisateurs.
- **Rapports PDF** : Génération automatique des bordereaux d'affectation.

## 🛠️ Installation & Démarrage

### Pré-requis
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite (ou MySQL)

### Installation
1. Clonez le dépôt : `git clone <votre-url-github>`
2. Installez les dépendances PHP : `composer install`
3. Installez les dépendances JS : `npm install`
4. Copiez le fichier d'environnement : `cp .env.example .env`
5. Générez la clé de l'application : `php artisan key:generate`
6. Préparez la base de données : `touch database/database.sqlite`
7. Exécutez les migrations et les seeders : `php artisan migrate:fresh --seed`

### Lancement
- **Serveur PHP** : `php artisan serve`
- **Compilation Assets** : `npm run dev`

## 👥 Rôles Utilisateurs (Test)
Tous les comptes utilisent le mot de passe : `password`
- **Admin** : `admin@dgi.ci`
- **Agent** : `agent@dgi.ci`
- **DSI** : `dsi@dgi.ci`

---
*GESPARC v1.0 - Un projet moderne pour une administration efficace.*
