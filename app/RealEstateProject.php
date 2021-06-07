<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RealEstateProject extends Model
{
    use HasFactory, SoftDeletes;

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function location()
    {
        return $this->belongsTo(Location::class)->select('id', 'name');
    }
    public function promoter()
    {
        return $this->belongsTo(Promoter::class)->select('id', 'promoter_type_id', 'name');
    }

    /**
     *
     * Devulve la ruta de las imágenes
     *
     */
    public function imagePath()
    {
        return \Storage::disk('s3')->url('brochures/images/real-estate-projects/');
    }
}
