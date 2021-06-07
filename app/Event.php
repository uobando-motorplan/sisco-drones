<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

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
