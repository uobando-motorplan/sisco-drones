<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use SoftDeletes;

    // Condición del producto
    const NUEVO = 'N';
    const USADO = 'U';
    const SIN_PREFERENCIA = 'S';
    // Uso del producto
    const PERSONAL = 'P';
    const TRABAJO = 'T';
    // Desde dónde se creó
    const DRONES_WEB = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id', 'preference_id', 'condition', 'comment', 'drone_comment', 'product_use', 'reserved_property',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['attended_at', 'tracing_date'];

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function source()
    {
        return $this->belongsTo(Source::class)->select('id', 'channel_id', 'city_id', 'name');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class)->select('id', 'product_id', 'amount', 'monthly_payment');
    }
    public function preference()
    {
        return $this->belongsTo(Preference::class)->select('id', 'product_id', 'name');
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
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id')->select('id', 'group_id', 'name', 'last_name', 'seller_code', 'cell_number', 'email');
    }
    public function group()
    {
        return $this->belongsTo(Group::class)->select('id', 'supervisor_id', 'province_id', 'name');
    }
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id')->select('id', 'name', 'last_name');
    }
    public function drone()
    {
        return $this->belongsTo(User::class, 'drone_id')->select('id', 'name', 'last_name', 'cell_number', 'email');
    }

    /**
     *
     * Relación uno a muchos
     *
     */
    public function observations()
    {
        return $this->hasMany(QuotationObservation::class, 'quotation_id');
    }
    public function seguimientos()
    {
        return $this->hasMany(QuotationObservation::class, 'quotation_id')
            ->select('id')
            ->where('user_id', '!=', \App\User::SISTEMA)
            ->whereNotNull('quotation_observations.status_id')
            ->whereNotNull('quotation_observations.score_id');
    }
    public function brochures()
    {
        return $this->hasMany(Brochure::class);
    }

    /**
     *
     * Relación uno a uno
     *
     */
    public function commission_detail()
    {
        return $this->hasOne(CommissionDetail::class);
    }
}
