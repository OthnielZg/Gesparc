<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = ['nom_service', 'id_direction'];

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'id_direction');
    }

    public function bureaux()
    {
        return $this->hasMany(Bureau::class, 'id_service');
    }
}
