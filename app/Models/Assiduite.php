<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assiduite extends Model
{
    protected $fillable = ['inscription_id', 'taux'];
    public $timestamps = false;

    protected $casts = [
        'taux'       => 'float',
        'updated_at' => 'datetime',
    ];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }
}
