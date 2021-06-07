<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationObservation extends Model
{
    use SoftDeletes;

    const FECHA_CREACION = 1;
    const FECHA_SEGUIMIENTO = 2;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['tracing_date'];

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'role_id', 'name', 'last_name');
    }
    public function status()
    {
        return $this->belongsTo(Status::class)->select('id', 'description');
    }
    public function closure_reason()
    {
        return $this->belongsTo(ClosureReason::class)->select('id', 'description');
    }
    public function score()
    {
        return $this->belongsTo(Score::class)->select('id', 'description');
    }
}
