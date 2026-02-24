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
    ];

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'REF-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Status labels
    public static function statutLabel(): array
    {
        return [
            'pre_demande'           => 'Pré-demande',
            'en_attente_dr'         => 'En attente DR',
            'transmise_gestionnaire'=> 'Chez le Gestionnaire',
            'proposition_sdrevt'    => 'Proposition au SDREVT',
            'en_attente_dsi'        => 'En attente DSI',
            'validee'               => 'Validée',
            'rejetee'               => 'Rejetée',
            'annulee'               => 'Annulée',
            'en_commande'           => 'En commande',
        ];
    }

    public static function statutColors(): array
    {
        return [
            'pre_demande'           => 'blue',
            'en_attente_dr'         => 'yellow',
            'transmise_gestionnaire'=> 'orange',
            'proposition_sdrevt'    => 'purple',
            'en_attente_dsi'        => 'indigo',
            'validee'               => 'green',
            'rejetee'               => 'red',
            'annulee'               => 'gray',
            'en_commande'           => 'yellow',
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
