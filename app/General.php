<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection as Collection;
use App\Libraries\DynamicMenu;

class General extends Model
{

    //  * S1. Obtiene la informacion de la persona con el numero de carnet
    //  * parametros [id: numero de carnet de identidad ]    
    public static function GetPersonByIdentityCard($identityCard)
    {
        $query = "select * from public.ff_datos_persona('" . $identityCard . "')";
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

    // *** - funcion para el registro de usuarios - ***
    // *** - parametros [dip: numero de carnet de identidad de la persona] - ***
    public static function RegisterUser($personal)
    {
        $query = "select * from app.ff_registrar_usuario('" . $personal . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de los perfiles del usuario - ***
    // *** - parametros [username: nombre de usuario de la persona, year: año de los perfiles] - ***
    public static function SearchUserProfiles($username, $year)
    {
        $query = "select * from app.ff_perfiles_usuario('" . $username . "','" . $year . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - funcion para la busqueda de las gestiones registradas para el usuario - ***
    // *** - parametros [username: nombre de usuario de la persona] - ***
    public static function SearchUserYears($username)
    {
        $query = "select * from app.ff_gestiones_usuario('" . $username . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetPersonsByDescriptionWithPagination($description)
    {
        $query = "select * from pub.ff_datos_persona('" . $description . "')";
        \Log::info($query);
        //$query = "select * from public.personas where paterno ='".$description."' order by paterno, materno, nombres";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


    public static function GetPersonsByDescription($description)
    {
        \Log::info("Description: " . $description);
        if ($description == '')
            # code...
            $query = "select * from public.ff_buscar_personas('')";
        else
            $query = "select * from public.ff_buscar_personas('" . $description . "')";
        \Log::info($query);
        //$query = "select * from public.personas where paterno ='".$description."' order by paterno, materno, nombres";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


    //  * S3. Actualiza la informacion de una persona que se encuentra registrada.
    // *** - parametros [carnet de identidad, nombres, apellido paterno, apellido materno, sexo, fecha de nacimiento] - ***
    public static function UpdatePerson($identityCard, $names, $paternal, $maternal, $sex, $birthdate)
    {
        $query = "select * from public.ff_editar_persona('" . $identityCard . "', '" . $paternal . "', '" . $maternal . "', '" . $names . "','" . $sex . "', '" . $birthdate . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }



    // *** - funcion para la busqueda de los usuarios  [db: app.users]- ***
    // *** - parametros [description: descripcion de la busqueda] - ***
    public static function GetUsersByDescription($description)
    {
        if ($description == '')
            # code...
            $query = "select * from app.users order by nodip, descripcion, usuario";
        else
            $query = "select * from app.users where nodip ='" . $description . "' order by nodip, descripcion, usuario";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


    // *** - funcion para la busqueda de las personas por carnet de identidad - ***
    // *** - parametros [carnet de identidad] - ***

    public static function GetUserByIdentityCard($identityCard)
    {
        $query = "select * from public.ff_mostrar_usuario('" . $identityCard . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function getNewCodDocument()
    {
        $idmax = DB::table('bdoc.des_doc')->max('cod_con');
        $newId = ((int)$idmax) + 1;
        $cad = '0000' . $newId;
        return substr($cad, strlen($cad) - 4);
    }
    public static function saveNewCall($cod_con, $glosa, $fec_pre,$ges,$path)
    {
        $cod_con = self::getNewCodDocument();
        $id_doc = 'NDEU';
        $date = Date('d-m-Y H:i:s');
        $valido = true;
        $query = " insert into 
        bdoc.des_doc
                ( 
                id_doc,
                cod_con,
                glosa,
                valido,
                fec_pre,
                fec_cre,
                ges,
                ruta
                )
                values
                 (
                '" . $id_doc . "',
                '" . $cod_con . "',
                '" . $glosa . "',
                '" . $valido . "',
                '" . $fec_pre . "',
                '" . $date . "',
                '" . $ges . "',
                '" . $path . "'
                );";
        $data = collect(DB::select(DB::raw($query)));
        return ['data' => $data, 'cod_con' => $cod_con];
    }
}
