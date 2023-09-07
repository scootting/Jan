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

}
