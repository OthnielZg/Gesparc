<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bureau extends Model
{
    protected $table = 'bureaux';
    protected $fillable = ['nom_bureau', 'id_service'];

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function utilisateurs()
    {
        return $this->hasMany(User::class, 'id_bureau');
    }
}
