# 🏦 GESPARC (DGI) - Portail de Documentation Ultime

Bienvenue sur le dépôt de **GESPARC**, l'application de gestion de parc informatique de la DGI.

Ce projet a été documenté avec un soin extrême pour permettre à n'importe quel développeur, même débutant, de comprendre **chaque ligne de code**.

---

## 📚 Les 3 piliers de la documentation

### 🧠 [Guide Backend Hyper-Deep Dive](README_BACKEND.md)
Le "Cerveau" de l'application. Découvrez comment Laravel gère vos demandes, les contrôleurs de workflow et la sécurité par rôles, expliqués pas à pas.

### 🎨 [Guide Frontend Hyper-Deep Dive](README_FRONTEND.md)
Le "Visage" de l'application. Découvrez le design system Premium (Tailwind CSS v3), les composants Blade réutilisables et l'interactivité avec Alpine.js.

### 🗄️ [Guide Database & Schémas](README_DATABASE.md)
La "Mémoire" de l'application. Apprenez comment sont structurées vos tables et comment les migrations créent vos étagères de données automatiquement.

---

## 📋 Installation en 1 Minute (Droit au but)

```bash
# 1. Installer les moteurs (PHP & Design)
composer install
npm install

# 2. Configurer l'environnement
cp .env.example .env
php artisan key:generate

# 3. Préparer la base de données (Commande magique)
php artisan migrate:fresh --seed

# 4. Lancer le site
php artisan serve   # Puis ouvrir http://127.0.0.1:8000
npm run dev         # Laisser tourner pour le design
```

---
*Fait avec ❤️ pour la Direction Générale des Impôts.*
