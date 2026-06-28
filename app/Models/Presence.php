<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    protected $fillable = ['inscription_id', 'date', 'statut', 'observation'];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }
}
