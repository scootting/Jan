<?php

namespace App\Http\Controllers;

use App\Solvency;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SolvencyController extends Controller
{
    //
    //  * S1. Obtiene la lista de documentos de las personas deudoras a traves de su descripcion
    //  * Route::post('getDebtorsDocument/', 'SolvencyController@getDebtorsDocument');
    public function getDebtorsDocument(Request $request)
    {
        $descripcion = strtoupper($request->get('description')); // '' cadena vacia
        $data = Solvency::GetDebtorsDocument($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getDebtorsDocument')]
        );
        return json_encode($paginate);
    }

    //  * S2. Agregar un nuevo documento de deudor
    //  * Route::post('storeDebtorDocument/', 'SolvencyController@storeDebtorDocument');
    public function storeDebtorDocument(Request $request)
    {
        /*
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
        return json_encode($data);*/
    }
}
