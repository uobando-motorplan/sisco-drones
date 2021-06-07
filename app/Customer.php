<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    // Estados del prospecto
    const COMPLETO = 'C';

    // Tipos de identificación del prospecto
    const CEDULA = 'C';
    const RUC = 'R';
    const PASAPORTE = 'P';

    // Desde dónde se creó
    const DRONES_WEB = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id', 'contact_method_id', 'identification_type', 'identification', 'names', 'surnames', 
        'phone_number', 'cell_number', 'email', 'occupation_id', 'occupation_period_id', 'has_social_security', 
        'can_pay_down_payment', 'monthly_payment_capacity', 'has_applied_to_credit', 'why_didnt_buy'
    ];

    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function source()
    {
        return $this->belongsTo(Source::class)->select('id', 'channel_id', 'city_id', 'name');
    }
    public function city()
    {
        return $this->belongsTo(City::class)->select('id', 'province_id', 'name');
    }
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id')->select('id', 'group_id', 'name', 'last_name', 'cell_number', 'email');
    }
    public function assignator()
    {
        return $this->belongsTo(User::class, 'assignator_id')->select('id', 'name', 'last_name');
    }
    public function contact_method()
    {
        return $this->belongsTo(ContactMethod::class)->select('id', 'name');
    }
    public function contact_schedule()
    {
        return $this->belongsTo(ContactSchedule::class)->select('id', 'name');
    }
    public function media()
    {
        return $this->belongsTo(Media::class)->select('id', 'name');
    }
    public function drone()
    {
        return $this->belongsTo(User::class, 'drone_id')->select('id', 'name', 'last_name', 'cell_number', 'email');
    }
    public function occupation()
    {
        return $this->belongsTo(Occupation::class)->select('id', 'name');
    }
    public function occupation_period()
    {
        return $this->belongsTo(OccupationPeriod::class)->select('id', 'name');
    }

    /**
     *
     * Relación uno a muchos
     *
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
    public function observations()
    {
        return $this->hasMany(CustomerObservation::class, 'customer_id');
    }

    /*
     *
     * Utilidades
     *
     */
    public function getFullName()
    {
        return $this->surnames.' '.$this->names;
    }
    public function getIdentificationType()
    {
        $document = ['C' => 'Cédula', 'R' => 'RUC', 'P' => 'Pasaporte'];
        return $document[$this->identification_type];
    }
}
