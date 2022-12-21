<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Solvency extends Model
{
    //
    //  * S1. Obtiene la lista de documentos de las personas deudoras a traves de su descripcion
    //  * Route::post('getDebtorsDocument/', 'SolvencyController@getDebtorsDocument');
    public static function GetDebtorsDocument($description)
    {
        # code...
        if ($description == '') {
            $query = "select * from sol.ff_datos_deudor('')";
        } else {
            $query = "select * from sol.ff_datos_deudor('" . $description . "')";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
