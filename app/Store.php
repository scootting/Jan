<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Store extends Model
{
    //
    //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
    //  * parametros [description: descripcion que se esta buscando ]
    public static function GetMaterialsByDescription($description)
    {
        if ($description == '') {
            $query = "select * from alm.materiales m ";
        } else {
            $query = "select * from alm.materiales m where m.mat_des like '%" . $description . "%'";
        }

        \Log::info("Esta es la de materiales: " . $query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
