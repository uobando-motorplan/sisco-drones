<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Period extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['start_date', 'end_date'];

    /**
     *
     * Relación uno a muchos
     *
     */
    public function subperiods()
    {
        return $this->hasMany(Subperiod::class);
    }

    /*
     *
     * Devuelve el arreglo con todos los días del periodo
     *
     */
    public function getDays($today=null)
    {
        // Declare an empty array
        $period_days = array();

        // Use strtotime function
        $start_date = strtotime($this->start_date);
        $end_date = strtotime($today ? $today : $this->end_date);

        // Use for loop to store dates into array
        // 86400 sec = 24 hrs = 60*60*24 = 1 day
        for ($current_date = $start_date; $current_date <= $end_date; $current_date += (86400)) {
            $day = date('Y-m-d', $current_date);
            $period_days[] = $day;
        }

        return $period_days;
    }
}
