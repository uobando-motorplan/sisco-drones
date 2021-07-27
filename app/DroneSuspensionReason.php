<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DroneSuspensionReason extends Model
{
    use SoftDeletes;

    const PROCESO_INCONCLUSO = 1;
    const SOLICITUD_USUARIO = 2;
}
