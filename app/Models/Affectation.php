<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_bordereau',
        'id_demande',
        'id_utilisateur',
        'id_materiel',
        'etat_reception',
        'etat_retour',
        'date_affectation',
        'date_remise',
        'lieu_remise',
        'statut_bordereau',
        'date_retour',
        'observations',
    ];

    protected $casts = [
        'date_affectation' => 'datetime',
        'date_remise' => 'datetime',
        'date_retour' => 'datetime',
    ];

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;

        return 'BRD-'.$date.'-'.str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'id_demande');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }

    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'id_materiel');
    }
}
