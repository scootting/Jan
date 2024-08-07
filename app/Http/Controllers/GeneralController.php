<?php

namespace App\Http\Controllers;

use App\General;
use App\Libraries\DynamicMenu;
use App\Libraries\JWTFAuth;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class GeneralController extends Controller
{
    //
    //
    //  * Buscar a un usuario de el recurso.
    //  * {username: nombre de usuario, password: clave del usuario}
    public function searchUser(Request $request)
    {
        $data = General::SearchUser($request->get('username'), $request->get('password'));
        $token = JWTFAuth::ValidateDataCredential($data);
        $response = array(
            'access_token' => $token,
            'user' => $data,
        );
        return json_encode($response);
    }

    //  * Quitar el registro de un usuario en el recurso.
    public function logoutUser(Request $request)
    {
        return response()->json('Logged out successfully', 200);
    }

    //  *  A3. cambiar la contraseña personal del cliente
    //  * {pass_ant: password anterior, pass_act: password nuevo, pass_con: password confirmado}
    public function changePassword(Request $request)
    {
        $id = strtoupper($request->get('id'));
        $pass_actual = $request->get('actual');
        $pass_nuevo = $request->get('nuevo');
        $pass_confirma = $request->get('confirma');
        $data = General::ChangePassword($id, $pass_actual, $pass_nuevo, $pass_confirma);
        return json_encode($data);
    }
    //  * Registrar un usuario en el recurso.
    public function storeUser(Request $request)
    {
        $usuario = $request->get('usuario');
        $personal = $usuario['personal'];
        \Log::info($personal);
        $data = General::RegisterUser($personal);
        return $data;
    }

    public function registerUserProfiles(Request $request)
    {
        $usuario = $request->get('usuario');
        $gestion = $request->get('gestion');
        $data = General::SearchUserProfiles($usuario, $gestion);
        $profiles = DynamicMenu::GetDataOptions($data);
        return json_encode($profiles);
    }

    public function registerUserYears(Request $request)
    {
        $usuario = $request->get('usuario');
        $years = General::SearchUserYears($usuario);
        return json_encode($years);
    }

    //  * COM1. Obtener una lista de personas que coinciden con la descripcion.
    public function getPersonsByDescriptionWithPagination(Request $request)
    {
        $description = strtoupper($request->get('description')); // '' cadena vacia
        \Log::info("esta es la descripcion".$description);
        $data = General::GetPersonsByDescriptionWithPagination($description);
        return json_encode($data);
    }
    //  * COM2. Obtiene una lista de categorias programaticas que coinciden con la descripcion.
    public function getProgramCategoryDescriptionWithPagination(Request $request)
    {
        $description = strtoupper($request->get('description')); // '' cadena vacia
        \Log::info("esta es la descripcion".$description);
        $data = General::GetProgramCategoryDescriptionWithPagination($description);
        return json_encode($data);
    }

    //  * COM3. Obtiene una lista de valores universitarios que coinciden con la descripcion.
    public function getUniversityValuesDescriptionWithPagination(Request $request)
    {
        $description = strtoupper($request->get('description')); // '' cadena vacia
        $year = strtoupper($request->get('year')); // '' cadena vacia
        \Log::info("esta es la descripcion".$description);
        $data = General::GetUniversityValuesDescriptionWithPagination($description, $year);
        return json_encode($data);
    }

    public function getPersonsByDescription(Request $request)
    {

        $descripcion = strtoupper($request->get('descripcion')); // '' cadena vacia
        $data = General::GetPersonsByDescription($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;

        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/persons')]
        );
        return json_encode($paginate);
    }

    //  * S1. Obtiene la informacion de la persona con el numero de carnet
    //  * {id: numero de carnet de identidad}
    public function getPersonById($id)
    {
        $data = General::GetPersonByIdentityCard($id);
        return json_encode($data);
    }

    //  * S2. Guardar la informacion de una nueva persona que no se encuentra registrada.
    //  * S3. Actualiza la informacion de una persona que se encuentra registrada.
    //  * {persona: datos de la persona[id, nombres, apellido paterno, apellido materno, sexo, fecha de nacimiento]}
    public function storePerson(Request $request)
    {
        $persona = $request->get('persona');
        \Log::info("esta es la persona registrada: ".json_encode($persona));
        $personal = strtoupper($persona['nro_dip']);
        $nombres = strtoupper($persona['nombres']);
        $paterno = strtoupper($persona['paterno']);
        $materno = strtoupper($persona['materno']);
        $sexo = strtoupper($persona['id_sexo']);
        $nacimiento = $persona['fec_nacimiento'];
        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $data = General::AddPerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            case 'editar':
                $data = General::UpdatePerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            default:
                break;
        }
        return json_encode($data);
    }

    //  * Obtener una lista de usuarios de el recurso utilizado.
    //  * {parametro: tipo de busqueda por atributo, descripcion: descripcion de la busqueda}
    public function getUsersByDescription(Request $request)
    {

        $descripcion = strtoupper($request->get('descripcion'));
        $data = General::GetUsersByDescription($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;

        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/users')]
        );
        return json_encode($paginate);
    }

    //  * Obtener un usuario de el recurso utilizado.
    //  * {id: numero de carnet de identidad}
    public function getUserById($id)
    {
        $data = General::GetUserByIdentityCard($id);
        return json_encode($data);
    }

    public function getNewCodDocument()
    {
        $data = General::getNewCodDocument();
        return json_encode($data);
    }
    public function saveNewCall(Request $request)
    {
        //dd($request);
        $cod_con = $request->codigo;
        $glosa = $request->descripcion;
        $fec_pre = $request->date1;
        $ges = '2021';
        $path = $request->path;
        $data = General::saveNewCall($cod_con, $glosa, $fec_pre, $ges, $path);
        return json_encode($data);
    }

    public function uploadPDF(Request $request)
    {
        //dd($request);
        $dataSource = $request->get('datasource');
        $arrayData = json_decode($dataSource, true);
        //dd($arrayData);
        $codigo = $arrayData['codigo'];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $path = 'public/convocatorias/' . strval($codigo);
            $file->storeAs($path, $file_name);
        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        return response()->json(['success' => 'Cargo exitoso.', 'path' => '/' . 'storage/convocatorias' . '/' . $file_name]);
    }

}
