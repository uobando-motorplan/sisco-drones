<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Drone extends Model
{
    use SoftDeletes;

    /**
     *
     * Relación uno a muchos
     *
     */
    public function observations()
    {
        return $this->hasMany(DroneObservation::class);
    }

    /*
     *
     * Devuelve el nombre completo
     *
     */
    public function getFullName($names_first = false)
    {
        if ($names_first) {
            return $this->names.' '.$this->surnames;
        }

        return $this->surnames.' '.$this->names;
    }
}
