<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Archive extends Model
{
    //
    //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
    //  * parametros [description: descripcion que se esta buscando ]
    public static function GetArchivesByDescription($description)
    {
        if ($description == '') {
            $query = "select * from arch.doc d";
        } else {
            $query = "select * from arch.doc d where d.glosa like '%" . $description . "%'";
        }

        \Log::info("Esta es la consulta de archivos: " . $query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
    //  * parametros {id: identificador del archivo, year: gestion del archivo }
    public static function GetDocumentsbyArchive($archive, $year)
    {
        //SELECT * FROM arch.ff_documentos_archivo('00000001', '2019')
        $query = "SELECT * FROM arch.ff_documentos_archivo('" . $archive . "','" . $year . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * A3. Obtiene la lista de tipos de documentos que pertenecen a un archivo
    //  * parametros {description: descripcion del tipo de documento que se necesita }
    public static function getTypesDocument($description)
    {
        $query = "select * from arch.tipos t where t.tipo = '" . $description . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A6. Obtiene la lista de contenedores para archivar con una breve descripcion
    //  * parametros [description: descripcion que se esta buscando ]
    public static function getFileContainerByDescription($description)
    {
        if ($description == '') {
            $query = "select *, (select t.descr from arch.tipos t where t.id = c.id_tipo) as descr from arch.conte c";
        } else {
            $query = "select *, (select t.descr from arch.tipos t where t.id = c.id_tipo) as descr from arch.conte c where c.des_con like '%" . $description . "%'";
        }

        \Log::info("Esta es la consulta de archivos: " . $query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A7. Obtiene la lista de contenedores para archivar con una breve descripcion
    //  * parametros [glosa: descripcion del documento, tipo: archivo, documento, ubicacion ]
    public static function SaveFileContainer($glosa, $tipo, $gestion)
    {
        //$query = "select *, (select t.descr from arch.tipos t where t.id = c.id_tipo) as descr from arch.conte c where c.des_con like '%" . $description . "%'";
        //\Log::info("Esta es la consulta de archivos: " . $query);
        $query = '';
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


}
