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
        $perPage = 30;
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
        //  * Datos para la solicitud de documento de deuda
        \Log::info($request);
        $documento = $request->get('documento');
        $referencia = strtoupper($documento['referencia']);
        $fecha = strtoupper($documento['fecha']);

        $programa = $request->get('programa');
        $cod_prg = strtoupper($programa['cod_prg']);
        $des_prg = strtoupper($programa['cat_des']);

        $responsable = $request->get('responsable');
        $ci_resp = strtoupper($responsable['nro_dip']);
        $des_resp = strtoupper($responsable['des_per']);


        $ci_vobo = $ci_resp;

        $usuario = $request->get('usuario');
        $ci_elab = trim(strtoupper($usuario['nodip']));
        $gestion = strtoupper($usuario['gestion']);
        $usr_cre = $usuario['usuario'];
        $tipo = 'D';

        //$deudas = $request->get('deudas');
        $deudores = $request->get('deudores');

        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $id = Solvency::AddDebtorDocument($tipo, $fecha, $ci_elab, $ci_resp, $ci_vobo, $usr_cre, $gestion);
                $id_documento = $id[0]->{'ff_nuevo_documento_deuda'};
                \Log::info("este es el id de la nueva solicitud" . $id);
                //  * Deudores
                foreach ($deudores as $item) {
                    # code...
                    $ci_per = strtoupper($item['id']);
                    $des_per = strtoupper($item['des_per']);
                    $des_per1 = strtoupper($item['des_per1']);
                    $data = Solvency::AddDebtorToDocument($id_documento,$gestion, $fecha, $ci_per, $des_per, $des_per1, $referencia, $cod_prg, $des_prg, $usr_cre, $tipo);                    
                }
                return json_encode($id_documento);
                break;
            case 'editar':
                //$data = Solvency::UpdateDebtorDocument($id, $gestion, $fecha, $detalle, $cod_prg, $des_prg, $usr_cre, $ci_resp, $ci_elab, $id_ref);
                break;
            default:
                break;
        }
        return json_encode($id_documento);
    }

    public function getDebtsById(Request $request){
        $id_concepto = $request->get('id');
        $data = Solvency::getDebtsById($id_concepto);
        return json_encode($data);
    }
}
