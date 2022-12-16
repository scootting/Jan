<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as Collection;


class Solvency extends Model
{
    //
    //  * S1. Obtiene la lista de documentos de las personas deudoras a traves de su descripcion
    //  * Route::post('getDebtorsDocument/', 'SolvencyController@getDebtorsDocument');
    public static function GetDebtorsDocument($description)
    {
        //$query = "select * from sol.ff_datos_deudor('" . $description . "')";
        $query = "select * from pub.ff_datos_persona('" . $description . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    
}
