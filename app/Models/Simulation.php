<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'gas_type',
    'duration',
    'max_ppm',
    'final_ppm',
    'status',
    'failure_reason',
    'ppe_selected',
    'mitigation_action'
])]
class Simulation extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
