<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemPhoto extends Model
{
    use HasFactory, SoftDeletes;

    /**
     *
     * Devulve la ruta de las imágenes
     *
     */
    public function imagePath()
    {
        return \Storage::disk('s3')->url('brochures/images/items/photos/');
    }
}
