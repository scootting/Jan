<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    //  * SE1. Obtiene la lista de las ordenes de servicio realizadas
    public static function GetServicesOrder($tipo_transaccion, $gestion)
    {
        $query = "select * from rec.ordenes where gestion = '" . $gestion . "' order by fecha, idx";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


}
