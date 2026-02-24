<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bureau extends Model
{
    protected $table = 'bureaux';
    protected $fillable = ['nom_bureau', 'id_servic'];

    public function servic()
    {
        return $this->belongsTo(Servic::class, 'id_servic');
    }

    public function utilisateurs()
    {
        return $this->hasMany(User::class, 'id_bureau');
    }
}
