<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = ['nom', 'date_debut', 'date_fin'];

    public function inscriptions()
    {
        return $this->hasMany(Inscription::class, 'formations_id');
    }

    public function animations()
    {
        return $this->hasMany(Animation::class, 'formations_id');
    }
}
