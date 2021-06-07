<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentRequestDetail extends Model
{
    use SoftDeletes;

    const NO_PAGADA = 'N';
    const PAGADA = 'P';
    const ANULADA = 'A';// Venta caida

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quotation_id', 'value', 
    ];

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class)->select('id', 'customer_id', 'plan_id', 'discount', 'admission_application');
    }
}
