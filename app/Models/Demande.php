<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'quantite',
        'categorie_demandee',
        'motif_demande',
        'priorite',
        'statut',
        'id_utilisateur',
        'id_chef_cdi',
        'commentaire_gestionnaire',
        'materiel_propose',
        // Étape 5 — SDREVT
        'decision_sdrevt',
        'materiel_contre_propose',
        'justification_sdrevt',
        'reference_contre_propose',
        // Étape 6 — DSI
        'decision_dsi',
        'materiel_retenu_dsi',
        'commentaire_dsi',
    ];

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;

        return 'REF-'.$date.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Status labels
    public static function statutLabel(): array
    {
        return [
            'pre_demande' => 'Pré-demande',
            'en_attente_dr' => 'En attente DR',
            'transmise_gestionnaire' => 'Chez le Gestionnaire',
            'proposition_sdrevt' => 'Proposition au SDREVT',
            'en_attente_dsi' => 'En attente DSI',
            'validee_dsi' => 'Validée par DSI',
            'en_finalisation' => 'En finalisation',
            'finalisee' => 'Finalisée',
            'validee' => 'Validée',
            'rejetee' => 'Rejetée',
            'annulee' => 'Annulée',
            'en_commande' => 'En commande',
        ];
    }

    /** Indique si le SDREVT a soumis une contre-proposition */
    public function hasContreProposition(): bool
    {
        return $this->decision_sdrevt === 'contre_propose' && ! empty($this->materiel_contre_propose);
    }

    /** Retourne le matériel finalement retenu par le DSI pour affichage */
    public function getMaterielRetenuLabel(): string
    {
        return match ($this->decision_dsi) {
            'retient_sedmi' => (string) $this->materiel_propose,
            'retient_sdrevt' => (string) $this->materiel_contre_propose,
            'autre' => (string) $this->materiel_retenu_dsi,
            default => '—',
        };
    }

    public static function statutColors(): array
    {
        return [
            'pre_demande' => 'blue',
            'en_attente_dr' => 'yellow',
            'transmise_gestionnaire' => 'orange',
            'proposition_sdrevt' => 'purple',
            'en_attente_dsi' => 'indigo',
            'validee_dsi' => 'teal',
            'en_finalisation' => 'cyan',
            'finalisee' => 'green',
            'validee' => 'green',
            'rejetee' => 'red',
            'annulee' => 'gray',
            'en_commande' => 'yellow',
        ];
    }

    public function getStatutLabelAttribute(): string
    {
        return self::statutLabel()[$this->statut] ?? $this->statut;
    }

    public function getStatutColorAttribute(): string
    {
        return self::statutColors()[$this->statut] ?? 'gray';
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function chefCdi()
    {
        return $this->belongsTo(User::class, 'id_chef_cdi');
    }

    public function validations()
    {
        return $this->hasMany(Validation::class, 'id_demande')->orderBy('created_at');
    }

    public function affectation()
    {
        return $this->hasOne(Affectation::class, 'id_demande');
    }
}
