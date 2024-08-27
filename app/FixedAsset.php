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
}
