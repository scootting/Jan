<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Solvency extends Model
{
    //
    //  * SO1. Obtiene la lista de documentos de las personas deudoras a traves de su descripcion
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

    //  * SO2. Guardar la informacion de un nuevo documento de deuda.
    public static function AddDebtorDocument($tipo, $fecha, $ci_elab, $ci_resp, $ci_vobo, $usr_cre, $gestion)
    {
        //sol.ff_nuevo_documento_deuda (...)
        $query = "select * from sol.ff_nuevo_documento_deuda('" . $tipo . "','" . $fecha . "','" . $ci_elab . "','" . $ci_resp . "','" . $ci_vobo . "','" . $usr_cre . "'," . $gestion . ")";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * SO2. Guardar la informacion de un nuevo documento de deuda.
    public static function AddDebtorToDocument($id_documento, $gestion, $fecha, $ci_per, $des_per, $des_per1, $referencia, $cod_prg, $des_prg, $usr_cre, $tipo)
    {
        $query = "select * from sol.ff_deudor_documento(" . $id_documento . ",'" . $gestion . "','" . $fecha . "','" . $ci_per . "','" . $des_per . "','" . $des_per1 . "','" . $referencia .
            "','" . $cod_prg . "','" . $des_prg . "','" . $usr_cre . "','" . $tipo . "')";
        $data = collect(DB::select(DB::raw($query)));
    }

    //  * SO2. Guardar la informacion de un nuevo documento de deuda.
    public static function AddDebtToDocument($id_conceptos, $tipo, $cantidad, $descripcion)
    {
        $query = "INSERT INTO sol.con_deudas(id_con, tip_deu, can_deu, des_deu) " .
            "VALUES('" . $id_conceptos . "','" . $tipo . "','" . $cantidad . "','" . $descripcion . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;

    }

    // * SO3. Obtiene la informacion para editar el documento de deuda
    public static function GetDebtorDocument($id_concepto)
    {
        $query = "select * from sol.do a where a.id_conceptos = '" . $id_concepto . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    // * SO3. Obtiene la informacion para editar el documento de deuda
    public static function GetDebtorOfDocument($id_concepto)
    {
        $query = "select * from sol.conceptos a where a.id_conceptos = '" . $id_concepto . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
