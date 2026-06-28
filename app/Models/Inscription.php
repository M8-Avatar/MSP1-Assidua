<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    protected $fillable = ['user_id', 'formations_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class, 'formations_id');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class);
    }

    public function assiduite()
    {
        return $this->hasOne(Assiduite::class);
    }
}
