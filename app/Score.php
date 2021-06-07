<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    const VENTA_CAIDA = 0;
    // const TELEFONO_INCORRECTO = 1;
    // const NO_ESTA_INTERESADO = 2;
    // const ENVIAR_INFO_MAIL = 3;
    // const SE_DIO_INFO_CPMP = 4;
    const CLIENTE_INDECISO = 5;
    // const CLIENTE_INTERESADO = 6;
    // const CLIENTE_PARA_CIERRE = 7;
    // const VENTA_CONFIRMADA_FALTA_PAGO = 8;
    // const VENTA_CON_ABONO = 9;
    const VENTA_CERRADA = 10;
}
