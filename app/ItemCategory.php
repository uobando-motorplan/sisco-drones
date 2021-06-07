<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemCategory extends Model
{
    use HasFactory, SoftDeletes;

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
