# 🧠 Hyper Deep Dive : Backend (L'Intelligence Totale)

Ce guide est l'encyclopédie technique de Gesparc. Nous allons analyser **chaque ligne stratégique** pour comprendre comment l'application réfléchit.

--- 

## 🏛️ 1. Gestion des Demandes : `DemandeController.php`
C'est le chef d'orchestre des besoins des agents.

### A. Filtrage Intelligent (Ligne 21)
```php
if ($user->hasRole(['agent'])) {
    $query->where('id_utilisateur', $user->id); // L'agent ne voit que SES dossiers
} elseif ($user->hasRole(['chef_cdi'])) {
    $idService = $user->bureau->id_service;
    $query->where('id_utilisateur', $user->id)
          ->orWhereHas('utilisateur.bureau', function ($q) use ($idService) {
              $q->where('id_service', $idService); // Le Chef voit TOUT son service
          });
}
```
*Analyse* : Ce bloc garantit la confidentialité. Un agent ne peut pas espionner les demandes de son collègue, mais son chef de service a une vue d'ensemble sur son équipe.

---

## 🕹️ 2. Moteur du Workflow : `ValidationController.php`
C'est ici que les décisions (OK / KO) sont traitées.

### A. La Transaction de Validation (Ligne 87)
```php
return DB::transaction(function () use ($user, $demande, $validated, $request) {
    // 1. Enregistre qui a validé et quand
    Validation::create([ ... ]);

    // 2. Si c'est un rejet (KO) (Ligne 99)
    if ($validated['decision'] === 'KO') {
        $demande->statut = ($user->role === 'DSI') ? 'annulee' : 'rejetee';
        $demande->save();
        return; // On arrête tout ici.
    }
    
    // 3. Si c'est OK → On calcule l'étape suivante
    if ($user->role === 'DR') $demande->statut = 'transmise_gestionnaire';
    elseif ($user->role === 'gestionnaire') {
        // Le gestionnaire doit lier un matériel réel (Ligne 119)
        $demande->materiel_propose = $materiel->marque.' '.$materiel->modele;
        $demande->statut = 'proposition_sdgrte';
    }
});
```
*Leçon Apprise* : L'utilisation de `DB::transaction` est une sécurité bancaire. Si le serveur plante au milieu de la validation, Laravel annule tout pour ne pas avoir une demande "moitié validée".

---

## � 3. Le Matériel : `MaterielController.php` & `AffectationController.php`
C'est la gestion concrète de l'inventaire.

### A. Finalisation de l'Affectation (Ligne 34 de AffectationController)
C'est l'étape ultime du moteur.
```php
public function storeFinalisation(Request $request, Demande $demande) {
    // 1. On crée l'affectation officielle
    Affectation::create([
        'id_demande' => $demande->id,
        'id_materiel' => $request->id_materiel,
        'id_utilisateur' => $demande->id_utilisateur,
    ]);

    // 2. On change le statut du matériel pour qu'il ne soit plus proposé ailleurs
    $materiel->update(['statut' => 'occupe']);
    
    // 3. Le dossier est enfin "FINALISÉ"
    $demande->update(['statut' => 'finalisee']);
}
```

---

## 👤 4. L'Objet Utilisateur : `User.php`
Un fichier central qui contient toutes les autorisations.

### A. Les Helpers de Rôle (Ligne 104)
```php
public function isAdmin(): bool { return $this->role === 'admin'; }
public function isDSI(): bool { return $this->role === 'DSI'; }
```
*Utilité* : On utilise ces fonctions partout dans le site (Blade) pour afficher ou cacher des menus. C'est beaucoup plus propre que d'écrire `@if($user->role == 'admin')`.

---

## 🛡️ 5. Le Middleware : `CheckRole.php` (Le Portier)
```php
public function handle(Request $request, Closure $next, string ...$roles) {
    // Si l'utilisateur n'est pas actif (Banni) (Ligne 17)
    if (!$request->user()->actif) {
        auth()->logout();
        return redirect()->route('login');
    }
}
```
*Crucial* : Ce fichier se lance à **chaque clic**. Il vérifie si vous avez toujours le droit d'être là.

---
*Ce guide Backend est complet. À chaque fois qu'une donnée bouge dans Gesparc, un de ces fichiers en est responsable.*
