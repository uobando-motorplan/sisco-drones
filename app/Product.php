<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const CASAPLAN = 1;
    const MOTORPLAN = 2;

    /**
     *
     * Relación uno a muchos
     *
     */
    public function plans()
    {
        return $this->hasMany(Plan::class)->select('id', 'product_id', 'name');
    }
    public function preferences()
    {
        return $this->hasMany(Preference::class)->select('id', 'product_id', 'name');
    }
}
