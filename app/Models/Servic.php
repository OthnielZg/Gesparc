<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servic extends Model
{
    protected $table = 'servics';
    protected $fillable = ['nom_servic', 'id_direction'];

    public function direction()
    {
        return $this->belongsTo(Direction::class, 'id_direction');
    }

    public function bureaux()
    {
        return $this->hasMany(Bureau::class, 'id_servic');
    }
}
