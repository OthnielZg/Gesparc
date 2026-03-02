<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materiel extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_serie',
        'categorie',
        'marque',
        'modele',
        'statut',
        'prix_acquisition',
        'id_fournisseur',
        'description',
    ];

    public static function statutLabels(): array
    {
        return [
            'disponible'   => 'Disponible',
            'affecte'      => 'Affecté',
            'maintenance'  => 'En maintenance',
            'hors_service' => 'Hors service',
        ];
    }

    public static function statutColors(): array
    {
        return [
            'disponible'   => 'green',
            'affecte'      => 'blue',
            'maintenance'  => 'yellow',
            'hors_service' => 'red',
        ];
    }

    public static function categories(): array
    {
        return [
            'Ordinateur Portable',
            'Ordinateur Bureau',
            'Écran',
            'Imprimante',
            'Scanner',
            'Serveur',
            'Switch / Réseau',
        ];
    }

    public function getStatutLabelAttribute(): string
    {
        return self::statutLabels()[$this->statut] ?? $this->statut;
    }

    public function getStatutColorAttribute(): string
    {
        return self::statutColors()[$this->statut] ?? 'gray';
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'id_materiel');
    }

    public function affectationActive()
    {
        return $this->hasOne(Affectation::class, 'id_materiel')->whereNull('date_retour');
    }
}
