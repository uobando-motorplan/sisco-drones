<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id')->select('id', 'name', 'last_name');
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id')->select('id', 'name');
    }
}
