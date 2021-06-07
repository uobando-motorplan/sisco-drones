<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrochureDetail extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brochure_id', 'item_id'
    ];
    
    /**
     *
     * Relación uno a uno
     *
     */
    public function item()
    {
        return $this->hasOne(Item::class);
    }
}
