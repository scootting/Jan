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

    //  * S2. Guardar la informacion de un nuevo documento de deuda.
    public static function AddDebtorDocument($gestion, $fecha, $detalle, $cod_prg, $des_prg, $usr_cre, $ci_resp, $ci_elab, $id_ref)
    {
        //sol.ff_nuevo_documento_deuda (...)
        $query = "select * from sol.ff_nuevo_documento_deuda('" . $gestion . "','" . $fecha . "','" . $detalle . "','" . $cod_prg . "','" . $des_prg
            . "','" . $usr_cre . "','" . $ci_resp . "','" . $ci_elab . "','" . $id_ref . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * S2. Guardar la informacion de un nuevo documento de deuda.
    public static function AddDebtorToDocument($id_conceptos, $ci_per, $des_per, $des_per1)
    {
        $query = "INSERT INTO sol.con_deudor(id_con, ci_per, des_per, des_per1) " .
            "VALUES('" . $id_conceptos . "','" . $ci_per . "','" . $des_per . "','" . $des_per1 . "')";
        $data = collect(DB::select(DB::raw($query)));
    }

    //  * S2. Guardar la informacion de un nuevo documento de deuda.
    public static function AddDebtToDocument($id_conceptos, $tipo, $cantidad, $descripcion)
    {
        $query = "INSERT INTO sol.con_deudas(id_con, tip_deu, can_deu, des_deu) " .
            "VALUES('" . $id_conceptos . "','" . $tipo . "','" . $cantidad . "','" . $descripcion . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;

    }
}
