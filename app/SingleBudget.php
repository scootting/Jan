<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class SingleBudget extends Model
{
    //  * PIN1. Obtener una lista de los presupuestos individuales de el recurso utilizado.
    //  * {presupuesto individual: datos de la persona[descripcion, usuario, gestion]}
    public static function getSingleBudget($year, $user)
    {
        $query = "select * from presi.ff_mostrar_presupuesto_invididual('','" . $year . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
