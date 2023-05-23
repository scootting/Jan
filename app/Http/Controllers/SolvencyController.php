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
        $documento = $request->get('documento');
        $id_ref = strtoupper($documento['id']);
        $detalle = strtoupper($documento['referencia']);
        $fecha = strtoupper($documento['fecha']);

        $programa = $request->get('programa');
        $cod_prg = strtoupper($programa['cod_prg']);
        $des_prg = strtoupper($programa['des_prg']);

        $responsable = $request->get('responsable');
        $ci_resp = strtoupper($responsable['nro_dip']);

        $usuario = $request->get('usuario');
        $ci_elab = trim(strtoupper($usuario['nodip']));
        $gestion = strtoupper($usuario['gestion']);
        $usr_cre = $usuario['usuario'];
        $deudas = $request->get('deudas');
        $deudores = $request->get('deudores');

        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $id = Solvency::AddDebtorDocument($gestion, $fecha, $detalle, $cod_prg, $des_prg, $usr_cre, $ci_resp, $ci_elab, $id_ref);
                $id_conceptos = $id[0]->{'ff_nuevo_documento_deuda'};
                \Log::info("este es el id de la nueva solicitud" . $id);
                //  * Deudas
                foreach ($deudas as $item) {
                    # code...
                    $tipo = $item['tipo'];
                    $cantidad = $item['cant'];
                    $descripcion = strtoupper($item['desc']);
                    $data = Solvency::AddDebtToDocument($id_conceptos, $tipo, $cantidad, $descripcion);
                }
                //  * Deudores
                foreach ($deudores as $item) {
                    # code...
                    $ci_per = strtoupper($item['id']);
                    $des_per = strtoupper($item['des_per']);
                    $des_per1 = strtoupper($item['des_per1']);
                    $data = Solvency::AddDebtorToDocument($id_conceptos, $ci_per, $des_per, $des_per1);
                }
                break;
            case 'editar':
                //$data = Solvency::UpdateDebtorDocument($id, $gestion, $fecha, $detalle, $cod_prg, $des_prg, $usr_cre, $ci_resp, $ci_elab, $id_ref);
                break;
            default:
                break;
        }
        return json_encode($data);
    }

    public function getDebtsById(Request $request){
        $id_concepto = $request->get('id');
        $data = Solvency::getDebtsById($id_concepto);
        return json_encode($data);
    }
}
