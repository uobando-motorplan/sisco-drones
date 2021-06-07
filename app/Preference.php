<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preference extends Model
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

    /**
     *
     * Devulve la ruta de las imágenes
     *
     */
    public function imagePath()
    {
        return \Storage::disk('s3')->url('brochures/images/brands/');
    }
}
