<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentRequest extends Model
{
    use SoftDeletes;

    const NO_PAGADA = 'N';
    const PAGADA = 'P';
    const ANULADA = 'A';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value', 
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['paid_at'];

    /**
     *
     * Relación uno a muchos
     *
     */
    public function details()
    {
        return $this->hasMany(PaymentRequestDetail::class)->withTrashed();
    }

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function drone()
    {
        return $this->belongsTo(User::class, 'drone_id')->select('id', 'name', 'last_name', 'email', 'cell_number');
    }    

    /*
     *
     * Utilidades
     *
     */
    public function getStatus()
    {
        switch ($this->status) {
            case PaymentRequest::NO_PAGADA:
                return '<span class="text-danger">No pagada</span>';
                break;
            case PaymentRequest::PAGADA:
                return '<span class="text-success">Pagada</span>';
                break;
            case PaymentRequest::ANULADA:
                return '<span class="text-warning">Anulada</span>';
                break;
        }
    }
}
