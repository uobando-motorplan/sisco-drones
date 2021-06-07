<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;

    /**
     *
     * Relación uno a muchos
     *
     */
    public function cities()
    {
        return $this->hasMany(City::class)->select('id', 'privince_id', 'name');
    }
}
