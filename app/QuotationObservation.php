<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationObservation extends Model
{
    use SoftDeletes;

    const FECHA_CREACION = 1;
    const FECHA_SEGUIMIENTO = 2;
    // Tipo
    const SEGUIMIENTO = 'S';
    const OBSERVACION = 'O';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quotation_id', 'user_id', 'followup_type_id', 'status_id', 'closure_reason_id', 
        'score_id', 'observation', 'followup_date', 'admission_application', 'type'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['followup_date'];


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
