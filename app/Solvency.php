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

    public static function GetDebtorsDocumentByYear($description, $year)
    {
        # code...
        if ($description == '') {
            $query = "select * from sol.ff_deudor_gestion('','" . $year . "')";
        } else {
            $query = "select * from sol.ff_deudor_gestion('" . $description . "','". $year ."')";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


    //  * SO2. Guardar la informacion de un nuevo documento de deuda.
    public static function AddDocument($tipo, $fecha, $ci_elab, $ci_resp, $ci_vobo, $usr_cre, $gestion)
    {
        //sol.ff_nuevo_documento_deuda (...)
        $query = "select * from sol.ff_registrar_documento('" . $tipo . "','" . $fecha . "','" . $ci_elab . "','" . $ci_resp . "','" . $ci_vobo . "','" . $usr_cre . "'," . $gestion . ")";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * SO2. Guardar la informacion de un nuevo documento de deuda.
    public static function AddDebtorDocument($id_documento, $gestion, $fecha, $ci_per, $des_per, $des_per1, $referencia, $cod_prg, $des_prg, $usr_cre, $tipo)
    {
        $query = "select * from sol.ff_registrar_deudor_documento(" . $id_documento . ",'" . $gestion . "','" . $fecha . "','" . $ci_per . "','" . $des_per . "','" . $des_per1 . "','" . $referencia .
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
    public static function GetDocument($id, $typed)
    {
        $query = "select * from sol.ff_datos_documento('" . $id . "', '". $typed . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    // * SO3. Obtiene la informacion para editar el documento de deuda
    public static function GetDocumentDetails($id, $typed)
    {
        $query = "select * from sol.ff_datos_documento_detalle('" . $id . "', '". $typed . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // * SO3. Obtiene la informacion para editar el documento de deuda
    public static function GetDocumentDigital($id, $typed)
    {
        $query = "select id, tipo, referencia, id_documento from sol.digitales d where d.id_documento = '" . $id . "' and d.tipo ='". $typed . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }



    // * SO4 Guarda los documentos digitalizados de las deudas
    public static function StoreDigitalDocument($id, $des_doc, $escaped)
    {
        $query = "INSERT INTO sol.digitales(id_documentos, descripcion, digital) VALUES (". $id .",'". $des_doc. "', '{$escaped}') ";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // * SO5 Obtiene los documentos digitalizados de las deudas
    public static function GetDigitalDocument($id){
        $query = "SELECT digital as pdf_data FROM sol.digitales d WHERE d.id_documentos = ?";
        $data = DB::select($query, [$id]);
        return $data;
    }

}
