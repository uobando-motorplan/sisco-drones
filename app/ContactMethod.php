<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactMethod extends Model
{
    use SoftDeletes;

    const VISITA_ASESOR_COMERCIAL = 3;
}
