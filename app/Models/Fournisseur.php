<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;
    protected $fillable = ['nom_fournisseur', 'email', 'telephone', 'adresse'];

    public function materiels()
    {
        return $this->hasMany(Materiel::class, 'id_fournisseur');
    }
}
