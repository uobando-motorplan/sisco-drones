<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function province()
    {
        return $this->belongsTo(Province::class)->select('id', 'name');
    }
}
