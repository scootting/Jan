<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as Collection;


class Archive extends Model
{
    //
    //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
    //  * parametros [description: descripcion que se esta buscando ]    
    public static function GetArchivesByDescription($description)
    {        
        if ($description == '')
            $query = "select * from arch.docto d";
        else
            $query = "select * from arch.docto d where d.glosa like '%" . $description . "%'";

        \Log::info("Esta es la consulta de archivos: ".$query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
    //  * parametros {id: identificador del archivo, year: gestion del archivo }     
    public static function getDocumentsbyArchive($archive, $year)
    {        
        //select * from arch.det_docto where id_doc = '00000001' and gestion = '2019';
        $query = "select * from arch.det_docto where id_doc = '" . $archive . "' and gestion = '". $year . "' order by indice asc";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * S2. Guarda los datos una nueva persona que no se encuentra registrada.
    // *** - parametros [carnet de identidad, nombres, apellido paterno, apellido materno, sexo, fecha de nacimiento] - ***
    public static function AddPerson($identityCard, $names, $paternal, $maternal, $sex, $birthdate)
    {
        $query = "select * from public.ff_registrar_persona('" . $identityCard . "', '" . $paternal . "', '" . $maternal . "', '" . $names . "','" . $sex . "', '" . $birthdate . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de usuarios - ***
    // *** - parametros [username: nombre de usuario, password: clave del usuario] - ***
    public static function SearchUser($username, $password)
    {
        $query = "select * from app.ff_login_usuario('" . $username . "','" . $password . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
