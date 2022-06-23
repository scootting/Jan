<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as Collection;
use App\Libraries\DynamicMenu;

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
    public static function AddMaterial($mat_cod, $mat_des, $mat_uni_med)
    {
        $query = "insert into alm.materiales(mat_cod, mat_des, mat_uni_med) ".
        "values('" . $mat_cod . "', '" . $mat_des . "', '" . $mat_uni_med .  "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
       
    public static function EditMaterial($mat_cod, $mat_des, $mat_uni_med)
    {
    $query = "update alm.materiales set  mat_des = '" . $mat_des . "', mat_uni_med = '" . $mat_uni_med .  "'".
        "where mat_cod = '" . $mat_cod . "'";
        
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function GetMaterialByIdentityCard($mat_cod)
    {
        if ($mat_cod == '') {
            $query = "select * from alm.materiales m ";
        } else {
            $query = "select * from alm.materiales m where m.mat_cod like '%" . $mat_cod . "%'";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
   
}
