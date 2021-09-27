<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventCategory extends Model
{
    use SoftDeletes;

    const SEGUIMIENTO = 1;
    const CITA = 2;
    const TURNO = 3;
}
