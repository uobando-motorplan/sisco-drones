<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function product()
    {
        return $this->belongsTo(Product::class)->select('id', 'name');
    }
}
