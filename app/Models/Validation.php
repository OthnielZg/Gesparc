<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_demande',
        'id_utilisateur',
        'decision',
        'niveau_validation',
        'commentaire',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class, 'id_demande');
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}
