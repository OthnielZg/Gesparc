<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ville extends Model
{
    protected $fillable = ['nom_ville'];

    public function directions()
    {
        return $this->hasMany(Direction::class, 'id_ville');
    }
}
