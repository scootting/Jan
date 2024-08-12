<?php

namespace App\Http\Controllers;

use App\Solvency;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

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

    public function getDebtorsDocumentByYear(Request $request)
    {
        $descripcion = strtoupper($request->get('description')); // '' cadena vacia
        $gestion = strtoupper($request->get('year')); // '' cadena vacia
        $data = Solvency::GetDebtorsDocumentByYear($descripcion, $gestion);
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
        $referencia = strtoupper($documento['detalle']);
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
                $id = Solvency::AddDocument($tipo, $fecha, $ci_elab, $ci_resp, $ci_vobo, $usr_cre, $gestion);
                $id_documento = $id[0]->{'ff_registrar_documento'};
                \Log::info("este es el id de la nueva solicitud" . $id);
                //  * Deudores
                foreach ($deudores as $item) {
                    # code...
                    $ci_per = strtoupper($item['id']);
                    $des_per = strtoupper($item['des_per']);
                    $des_per1 = strtoupper($item['des_per1']);
                    $data = Solvency::AddDebtorDocument($id_documento,$gestion, $fecha, $ci_per, $des_per, $des_per1, $referencia, $cod_prg, $des_prg, $usr_cre, $tipo);                    
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

    public function getDocumentDetails(Request $request){
        $id = $request->get('id');
        $tipo = $request->get('typed');
        $document = Solvency::GetDocument($id, $tipo);
        $documentDetails = Solvency::GetDocumentDetails($id, $tipo);
        return json_encode(['document' => $document, 'documentDetails' => $documentDetails]);
    }

    // * SO4. Guarda los documentos digitalizados de las deudas
    public function storeDigitalDocument(Request $request)
    {
        $id_documento = $request->get('id_doc');
        $year = $request->get('gestion');
        $fileExt = $request->file('file')->getClientOriginalExtension();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            //$path = "documento";
            //$file_name = 'documento-'. $year . '-' . strval($id_document).'.'. $fileExt;//
            //$ruta = $path . "/" . $file_name;
            //$file->storeAs($path, $file_name);
            //documento digital
            $descripcion = $file->getClientOriginalName();
            $data = file_get_contents($file);
            $escaped = pg_escape_bytea($data);
            $data = Solvency::StoreDigitalDocument($id_documento, $ruta, $escaped);
        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        return response()->json(['success' => 'Uploaded Successfully.']);
    }

    // * SO5. Obtiene los documentos digitalizados de las deudas
    public function getDigitalDocument(Request $request)
    {
        $id = $request->get('id');
        $year = $request->get('year');
        $result = Solvency::GetDigitalDocument($id);

        if (!empty($result[0]->pdf_data)) {
            $my_bytea = stream_get_contents($result[0]->pdf_data);
            return $my_bytea;
        } else {
            return response()->json([
                'error' => 'No se encontró ningún registro con el ID proporcionado.',
            ]);
        }
    }

}
