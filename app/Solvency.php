<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solvency extends Model
{
    //
    //  * S1. Obtiene la lista de documentos de las personas deudoras a traves de su descripcion
    //  * Route::post('getDebtorsDocument/', 'SolvencyController@getDebtorsDocument');
    public static function getDebtorsDocument($description)
    {
        //$query = "select * from pub.ff_datos_persona('" . $description . "')";
        $query = "select * from solvencias.ff_datos_deudores('" . $description . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    
}
