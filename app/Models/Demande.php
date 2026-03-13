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
        // Étape 5 — SDGRTE
        'decision_sdgrte',
        'materiel_contre_propose',
        'justification_sdgrte',
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

    // Libellés des statuts
    public static function statutLabel(): array
    {
        return [
            'pre_demande' => 'Pré-demande',
            'en_attente_dr' => 'En attente DR',
            'transmise_gestionnaire' => 'Chez le Gestionnaire',
            'proposition_sdgrte' => 'Proposition au SDGRTE',
            'en_attente_dsi' => 'En attente DSI',
            'validee_dsi' => 'Validée par DSI',
            'en_finalisation' => 'En finalisation',
            'finalisee' => 'Finalisée',
            'validee' => 'Validée',
            'rejetee' => 'Rejetée',
            'annulee' => 'Annulée',
        ];
    }

    public function hasContreProposition(): bool
    {
        return $this->decision_sdgrte === 'contre_propose' && ! empty($this->materiel_contre_propose);
    }

    /** Retourne le matériel finalement retenu par le DSI pour affichage */
    public function getMaterielRetenuLabel(): string
    {
        return match ($this->decision_dsi) {
            'retient_sedmi' => (string) $this->materiel_propose,
            'retient_sdgrte' => (string) $this->materiel_contre_propose,
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
            'proposition_sdgrte' => 'purple',
            'en_attente_dsi' => 'indigo',
            'validee_dsi' => 'teal',
            'en_finalisation' => 'cyan',
            'finalisee' => 'green',
            'validee' => 'green',
            'rejetee' => 'red',
            'annulee' => 'gray',
        ];
    }

    public function getStatutLabelAttribute(): string
    {
        return self::statutLabel()[$this->statut] ?? $this->statut;
    }

    public static function statutColorHex(): array
    {
        return [
            'pre_demande' => '#3b82f6', // blue-500
            'en_attente_dr' => '#eab308', // yellow-500
            'transmise_gestionnaire' => '#f97316', // orange-500 (DGI)
            'proposition_sdgrte' => '#a855f7', // purple-500
            'en_attente_dsi' => '#6366f1', // indigo-500
            'validee_dsi' => '#14b8a6', // teal-500
            'en_finalisation' => '#06b6d4', // cyan-500
            'finalisee' => '#22c55e', // green-500 (CI)
            'validee' => '#22c55e',
            'rejetee' => '#ef4444', // red-500
            'annulee' => '#64748b', // slate-500
        ];
    }

    public function getStatutColorHexAttribute(): string
    {
        return self::statutColorHex()[$this->statut] ?? '#94a3b8';
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
