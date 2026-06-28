<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerte extends Model
{
    protected $fillable = ['assiduite_id', 'date_alerte', 'vue_admin', 'vue_apprenant'];
    protected $table = 'alertes';
    public $timestamps = false;

    protected $casts = [
        'vue_admin'     => 'boolean',
        'vue_apprenant' => 'boolean',
        'date_alerte'   => 'datetime',
    ];

    public function assiduite()
    {
        return $this->belongsTo(Assiduite::class);
    }
}
