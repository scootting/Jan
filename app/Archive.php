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
            $query = "select * from arch.docto d";
        } else {
            $query = "select * from arch.docto d where d.glosa like '%" . $description . "%'";
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

    // *** - funcion para la busqueda de usuarios - ***
    // *** - parametros [username: nombre de usuario, password: clave del usuario] - ***
    public static function SearchUser($username, $password)
    {
        $query = "select * from public.ff_registrar_persona('" . $identityCard . "', '" . $paternal . "', '" . $maternal . "', '" . $names . "','" . $sex . "', '" . $birthdate . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
