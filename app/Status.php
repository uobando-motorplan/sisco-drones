<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const POR_GESTIONAR = 1;
    const PENDIENTE_POR_CONTACTO = 2;
    const POR_VISITAR = 3;
    const EN_SEGUIMIENTO = 4;
    const CERRADO = 5;
}
