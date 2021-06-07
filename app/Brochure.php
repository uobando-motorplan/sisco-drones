<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brochure extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quotation_id', 'user_id', 'slug'
    ];

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name', 'last_name');
    }
    public function quotation()
    {
        return $this->belongsTo(Quotation::class)->select('id', 'customer_id', 'plan_id');
    }

    /**
     *
     * Relación uno a muchos
     *
     */
    public function details()
    {
        return $this->hasMany(BrochureDetail::class)->select('id', 'brochure_id', 'item_id');
    }
}
