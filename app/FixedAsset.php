<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FixedAsset extends Model
{
    //
    public static function GetDocumentFixedAssetByYear($year, $type)
    {
        //$year = 2020;
        //$query = "select * from act.asignaciones aa where aa.tip_doc = '".$type."' and aa.gestion = '".$year."' and aa.estado = 'Verificado' order by aa.fec_cre desc";
        $query = "select * from act.asignaciones aa where aa.tip_doc in (1,3,2,4) and aa.gestion = '" . $year . "' and aa.estado = 'Verificado' order by aa.fec_cre desc";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //
    public static function getFixedAssetsbyDocument($document)
    {
        $query = "SELECT * FROM act.activos aa WHERE aa.codigo LIKE '" . $document . "%'";
        \Log::info("esta es una consulta mas: " . $query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetCategoryProgramatic($year, $description)
    {
        if ($description != "") {
            $query = "select cat_des as des_prg, cod_prg from public.sis_cat_pro a where a.cat_pro in ('00', '01', '02', '03', '10', '51', '61') and a.cat_sis = 'ACTIVIDAD' and a.cat_ano = " . $year . " and a.cat_des like '%" . $description . "%'";
            \Log::info("prueba: " . $query);
        } else {
            $query = "select cat_des as des_prg, cod_prg from public.sis_cat_pro a where a.cat_pro in ('00', '01', '02', '03', '10', '51', '61') and a.cat_sis = 'ACTIVIDAD' and a.cat_ano = " . $year . "";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //$dataMeasurement = FixedAsset::GetMeasurement($gestion);
    public static function GetMeasurement($year)
    {
        //unidad de medida
        $query = "SELECT * from com.f_unidad_medida()";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetAccountingItem($year)
    {
        //partida contable
        $query = "SELECT * FROM act.f_b_contable()";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //$dataBudgetItem= FixedAsset::GetBudgetItem($gestion);
    public static function GetBudgetItem($year)
    {
        //partida presupuestaria
        $query = "select * from act.f_b_partida('" . $year . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AC1. Obtiene la lista de categorias programaticas    
    //  * {year: gestion en la que se desarrolla}
    public static function GetDataPrograms($year)
    {
        $query = "select *, cat_des as value from public.sis_cat_pro d where d.cat_ano = '" . $year . "' and d.cat_sis = 'ACTIVIDAD'";
        \Log::info($query);
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AC2. Obtiene la lista de categorias programaticas    
    //  * {year: gestion en la que se desarrolla}
    public static function GetAssignments($description, $typea, $year)
    {
        $query = "select * from actx.ff_datos_asignacion('" . $description . "'," . $typea . ",'" . $year . "')";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AC4. Obtiene la lista de asignaciones detallado
    public static function GetAssignmentsById($id, $typea, $year)
    {
        $query = "select * from actx.ff_datos_asignacion_id('" . $id . "'," . $typea . ",'" . $year . "')";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


    //  * AC3. Guardar la nueva asignacion
    public static function StoreAssignments($tipo, $fecha, $cod_prg, $des_prg, $ci_resp, $ci_elab, $user, $gestion)
    {
        $query = "select * from actx.ff_registrar_asignacion(" . $tipo . ",'" . $fecha . "','" . $cod_prg . "','" . $des_prg . "','" . $ci_resp . "','" . $ci_elab . "','" . $user . "'," . $gestion . ")";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}


