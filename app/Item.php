<?php

namespace App;

use App\Plan;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory, SoftDeletes;

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class)->select('id', 'product_id', 'amount');
    }
    public function category()
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id')->select('id', 'product_id', 'name');
    }
    public function preference()
    {
        return $this->belongsTo(Preference::class)->select('id', 'product_id', 'name', 'image');
    }
    public function real_estate_project()
    {
        return $this->belongsTo(RealEstateProject::class);
    }

    /**
     *
     * Relación uno a muchos
     *
     */
    public function photos()
    {
        return $this->hasMany(ItemPhoto::class)->select('id', 'item_id', 'name');
    }
    public function videos()
    {
        return $this->hasMany(ItemVideo::class)->select('id', 'item_id', 'url', 'image');
    }

    /**
     *
     * Devulve la ruta de las imágenes
     *
     */
    public function imagePath()
    {
        return \Storage::disk('s3')->url('brochures/images/items/');
    }
}
