<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerObservation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'observation'
    ];
	
    /**
     *
     * Relación uno a muchos (Inversa)
     *
     */
    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'role_id', 'name', 'last_name');
    }
}
