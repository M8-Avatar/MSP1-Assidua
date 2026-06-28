<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Animation extends Model
{
    protected $fillable = ['user_id', 'formations_id'];

    public function formation()
    {
        return $this->belongsTo(Formation::class, 'formations_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
