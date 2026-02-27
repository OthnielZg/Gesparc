<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direction extends Model
{
    protected $fillable = ['nom_direction', 'id_ville'];

    public function ville()
    {
        return $this->belongsTo(Ville::class, 'id_ville');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'id_direction');
    }
}
