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
            $query = "select * from arch.doc d where id_tipo = 'A' order by id_doc desc";
        } else {
            $query = "select * from arch.doc d where d.glosa like '%" . $description . "%' and id_tipo = 'A' order by id_doc desc";
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
    public static function getTypesDocument($type)
    {
        if ($type == '') {
            $query = "select * from arch.tipos d order by d.idc desc";
        } else {
            //$query = "select * from arch.tipos t where t.tipo = '" . $description . "'";
            $query = "select * from arch.tipos d where idt ='" . $type . "' order by d.descr asc";
        }
        \Log::info($query);
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

    //  * A8. Obtiene un tipo de archivo en especifico
    public static function GetTypeArchiveById($id)
    {
        $query = "select * from arch.tipos d where d.id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * A9. Actualiza la informacion de un tipo de archivo
    public static function OnUpdateTypeArchive($id, $descripcion)
    {
        $query = "UPDATE arch.tipos set descr = '" . $descripcion . "' where id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A10. Guardar un nuevo tipo de archivo
    public static function OnStoreTypeArchive($descripcion, $tipo)
    {
        $query = "SELECT * FROM arch.ff_registrar_tipo('" . $descripcion . "','" . $tipo . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A11. Guardar los archivos del documento
    public static function addArchivesByDocument($documento, $indice, $numeral, $glosa, $fecha, $id_tipo, $id_arch, $gestion){
        //$query = "SELECT * FROM arch.ff_registrar_detalle_documento('" . $descripcion . "','" . $tipo . "')";
        $query = "INSERT INTO arch.doc2(id_doc, numeral, indice, glosa, fecha, id_arch, gestion, id_tipo) VALUES " .
        "('" . $documento . "','" . $numeral . "','" . $indice . "','" . $glosa . "','" .$fecha . "','" . $id_arch . "','" . $gestion . "','" . $id_tipo . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;

    }
    public static function deleteArchivesByDocument($documento){
        $query = "delete from arch.doc2 where id_doc = '" . $documento . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
