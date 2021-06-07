<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use SoftDeletes;

    const GRUPOS_DE_VENTAS = 'V';
    const DRONES = 'D';

    /**
     *
     * Relación uno a muchos
     *
     */
    public function files()
    {
        return $this->hasMany(File::class)->select('id', 'name', 'created_at');
    }
}
