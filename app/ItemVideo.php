<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemVideo extends Model
{
    use HasFactory, SoftDeletes;

    /**
     *
     * Devulve la ruta de las imágenes
     *
     */
    public function imagePath()
    {
        return \Storage::disk('s3')->url('brochures/images/items/videos/');
    }

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function embedCode()
    {
        return substr($this->url, strrpos($this->url, '/'), \strlen($this->url));
    }
}