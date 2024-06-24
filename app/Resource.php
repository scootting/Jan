<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Resource extends Model
{
    //  * RP11. Obtener la lista de programas academicos
    public static function GetTypesOfProgram()
    {
        $query = "select * from rec.tipos c order by idx";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * RP12. Guardar un curso de postgrado.
    public static function StoreProgram($descripcion, $costo, $fecha, $ci_tutor, $des_tutor, $id_tipo, $gestion)
    {
        $query = "select * from rec.ff_registrar_curso('" . $descripcion . "'," . $costo . ",'" . $fecha . "','" . $ci_tutor . "','" . $des_tutor . "'," . $id_tipo . ",'" . $gestion . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * RP1. Obtener la lista de cursos de posgrado.
    //  * parametros [description: descripcion que se esta buscando ]
    public static function GetPrograms($gestion)
    {
        $query = "select *, c.id id_programa from rec.programas c inner join rec.tipos d on c.id_tipo = d.id WHERE c.gestion = '" . $gestion . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

//  * RP2. Obtener un curso de posgrado.
    public static function GetProgramById($id)
    {
        $query = "select *, c.id id_programa from rec.programas c inner join rec.tipos d on c.id_tipo = d.id WHERE c.id = " . $id . "";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
//  * RP13. Obtener la lista de estudiantes por cursos de posgrado.
    public static function GetStudentsByProgram($id)
    {
        $query = "select ci_per, des_per, id_programa, estado, sum(deuda) as deuda, sum(pago) as pago, sum(saldo) as saldo from rec.programa_personas c WHERE c.id_programa = " . $id . " group by ci_per, des_per, id_programa, estado";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
//  * RP14. Agregar un grupo de estudiantes al curso.
    public static function StoreStudentByProgram($id, $ci_per, $des_per, $deuda, $pago, $saldo)
    {
        $query = "select * from rec.ff_registrar_curso_persona('" . $ci_per . "','" . $des_per . "'," . $id . "," . $deuda . "," . $pago . "," . $saldo . ")";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
//  * RP15. Cerrar el curso.
    public static function CloseFinallyProgram($id)
    {
        $query = "update rec.programas set estado = 'Verificado' WHERE id = " . $id. "";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
//  * RP16. mostrar los pagos de un estudiante.
    public static function GetStudentDetails($id, $id_estudiante)
    {
        $query = "select * from rec.programa_movimientos c WHERE c.ci_per = '" . $id_estudiante . "' and c.id_programa = " . $id ."";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * RP3. Obtiene un estudiante registrado en un curso.
    //Route::post('getStudentProgramById', 'DocumentController@getStudentProgramById');
    public static function GetStudentProgramById($id_programa, $ci_per)
    {
        $query = "select *, c.id as id_programa from rec.programas c inner join rec.programa_personas d on c.id = d.id_programa WHERE c.id = " . $id_programa . " and d.ci_per ='". $ci_per . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * RP4. Obtiene los tipos de pago.
    //Route::post('getFormSaleProgram', 'DocumentController@getFormSaleProgram');
    public static function GetFormSaleProgram()
    {
        $query = "select * from rec.metodo_pago";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * RP18. Obtener los pagos de un curso de postgrado.
    //Route::post('getSalesStudentByProgram', 'DocumentController@getSalesStudentByProgram');
    public static function GetSaleStudentByProgram ($id)
    {
        $query = "select * from rec.programa_movimientos c inner join rec.metodo_pago d on d.ids = c.id_metodo WHERE c.id_programa = " . $id . "";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * RP19. Agregar un pago de estudiantes al curso.
    //Route::post('storeSaleStudentProgram', 'DocumentController@storeSaleStudentProgram');
    public static function StoreSaleStudentProgram($id_programa, $ci_per, $des_per, $importe, $fecha, $id_metodo, $comprobante)
    {
        
        $query = "select * from rec.ff_registrar_persona_pago(" . $id_programa . ",'". $ci_per . "','" . $des_per . "'," . $importe . ",'" . $fecha . "'," . $id_metodo . ",'" . $comprobante . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    // *RP20. actualizar los pagos, tras guardar un movimiento.
    public static function UpdateMountStudentProgram($id_programa, $ci_per)
    {
        $query = "select * from rec.ff_actualizar_pagos(" . $id_programa . ",'". $ci_per . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
