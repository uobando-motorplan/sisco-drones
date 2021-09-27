<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    const AUTOMATICO = 'A';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'event_category_id', 'related_id', 'title', 'is_all_day', 'start_date', 'end_date', 'url', 'type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date'];

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'event_category_id');
    }
}
