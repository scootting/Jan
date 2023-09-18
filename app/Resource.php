<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Resource extends Model
{
    //  * RP1. Obtener la lista de cursos de posgrado.
    //  * parametros [description: descripcion que se esta buscando ]
    public static function GetCoursesOfPostgraduate($gestion)
    {
        $query = "select * from rec.conceptos c WHERE c.gestion = '" . $gestion . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    
    //  * RP2. Guardar un curso de postgrado.    
    public static function StoreCourseOfPostgraduate($curso_detalle, $tipo, $cod_prg, $cod_val, $pre_val, $gestion)
    {
        $query = "select * from rec.ff_registrar_curso('" . $curso_detalle . "','" . $tipo . "','" . $cod_prg . "','" . $cod_val . "'," . $pre_val . ",'" . $gestion . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * RP3. Obtiene las transacciones realizadas en caja universitaria del valorado - curso de postgrado
    public static function GetInputCourse($id, $gestion)
    {
        $query = "select * from rec.ff_ingresos_curso('" . $id .  "','" . $gestion . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * RP4. Guarda las transacciones conciliadas del curso de postgrado
    public static function StoreInputCourse($id, $ci_per, $cod_val, $des_per, $fec_tra, $gestion, $id_tran, $imp_val, $id_dia, $tip_tra, $usuario, $obs)
    {
        $query = "select * from rec.ff_registrar_ingreso_curso(" . $id . ",'" . $ci_per . "','" . $cod_val . "','" . $des_per . "','" . $fec_tra ."','" 
                 . $gestion . "'," . $id_tran . "," . $imp_val . "," . $id_dia . "," . $tip_tra . ",'" . $usuario ."','" . $obs . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * RP5. obtiene los ingresos  conciliados del curso de postgrado
    public static function getInputTransactionsOfCourse($id, $gestion)
    {
        $query = "select * from rec.concepto_flujo f where f.id_concepto = " . $id .  " and f.gestion = '" . $gestion . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
