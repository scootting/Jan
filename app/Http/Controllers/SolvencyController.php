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

    //  * SO8. Obtiene la lista de documentos de las personas regularizadas
    //  * Route::post('getDebtorsDocument/', 'SolvencyController@getCreditorsDocument');
    public function getCreditorsDocument(Request $request)
    {
        $descripcion = strtoupper($request->get('description')); // '' cadena vacia
        $data = Solvency::getCreditorsDocument($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 30;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getCreditorsDocument')]
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
        $parcial = $request->get('parcial');
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

        $deudores = $request->get('deudores');

        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $referencia = strtoupper($documento['detalle']);
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
                /*
                $referencia = strtoupper($parcial['detalle']);
                $id_documento = strtoupper($documento['id_documento']);
                foreach ($deudores as $item) {
                    $ci_per = strtoupper($item['id']);
                    $des_per = strtoupper($item['des_per']);
                    $des_per1 = strtoupper($item['des_per1']);
                    $data = Solvency::UpdateDebtorDocument($id_documento, $gestion, $fecha, $ci_per, $des_per, $des_per1, $referencia, $cod_prg, $des_prg, $usr_cre, $tipo, $id_persona);                    
                }
                return json_encode($id_documento);*/
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
        $documentDigital = Solvency::GetDocumentDigital($id, $tipo);
        return json_encode(['document' => $document, 'documentDetails' => $documentDetails, 'documentDigital' => $documentDigital]);
    }


    public function getDocumentRegDetails(Request $request){
        $id = $request->get('id');
        $tipo = $request->get('typed');
        $document = Solvency::GetDocumentReg($id, $tipo);
        $documentDetails = Solvency::GetDocumentRegDetails($id, $tipo);
        $documentDigital = Solvency::GetDocumentDigital($id, $tipo);
        return json_encode(['document' => $document, 'documentDetails' => $documentDetails, 'documentDigital' => $documentDigital]);
    }


    // * SO4. Guarda los documentos digitalizados de las deudas
    public function storeDigitalDocument(Request $request)
    {
        $id = $request->get('id');
        $referencia = $request->get('referencia');
        $fecha = $request->get('fecha');
        $fileExt = $request->file('file')->getClientOriginalExtension();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            //documento digital
            $descripcion = $file->getClientOriginalName();
            $data = file_get_contents($file);
            $escaped = pg_escape_bytea($data);
            $data = Solvency::StoreDigitalDocument($id,$referencia, $fecha, $escaped);
        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        return response()->json(['success' => 'Uploaded Successfully.']);
    }


    //  * EF4. Obtener documentos digitalizados
    public function getDigitalSolvencyDocument(Request $request)
    {
        $id = $request->get('id');
        $result = Solvency::GetDigitalSolvencyDocument($id);
        if (!empty($result[0]->pdf_data)) {
            $my_bytea = stream_get_contents($result[0]->pdf_data);
            return $my_bytea;
        } else {
            return response()->json([
                'error' => 'No se encontró ningún registro con el ID proporcionado.',
            ]);
        }
    }

    //  * S7. Agregar un nuevo documento de regularizacion
    public function storeCreditorDocument(Request $request)
    {
        //  * Datos para la solicitud de documento de deuda
        \Log::info($request);
        $documento = $request->get('documento');
        $documento_reg = $request->get('documento2');
        $fecha = strtoupper($documento_reg['fecha']);

        //$cod_prg = strtoupper($documento['cod_prg']);
        //$des_prg = strtoupper($documento['cat_des']);

        $responsable = $request->get('responsable');
        $ci_resp = strtoupper($responsable['nro_dip']);
        $des_resp = strtoupper($responsable['des_per']);

        $ci_vobo = $ci_resp;

        $usuario = $request->get('usuario');
        $ci_elab = trim(strtoupper($usuario['nodip']));
        $gestion = strtoupper($usuario['gestion']);
        $usr_cre = $usuario['usuario'];
        $tipo = 'R';

        $deudores = $request->get('deudores');
        $acredores = $request->get('acredores');

        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $referencia = strtoupper($documento_reg['detalle']);
                $id = Solvency::AddDocument($tipo, $fecha, $ci_elab, $ci_resp, $ci_vobo, $usr_cre, $gestion);
                $id_documento = $id[0]->{'ff_registrar_documento'};
                \Log::info("este es el id de la nueva solicitud" . $id);
                //  * Deudores
                foreach ($acredores as $item) {
                    # code...
                    $id_conceptos = strtoupper($item['id_conceptos']);
                    $ci_per = strtoupper($item['ci_per']);
                    $des_per = strtoupper($item['des_per']);
                    $cod_prg = strtoupper($item['cod_prg']);
                    $des_prg = strtoupper($item['des_prg']);
                    $des_per1 = strtoupper($item['des_per']);
                    $data = Solvency::AddCreditorDocument($id_documento,$gestion, $fecha, $ci_per, $des_per, $des_per1, $referencia, $cod_prg, $des_prg, $usr_cre, $tipo, $id_conceptos);                    
                }
                return json_encode($id_documento);
                break;                
            case 'editar':
                /*
                $referencia = strtoupper($parcial['detalle']);
                $id_documento = strtoupper($documento['id_documento']);
                foreach ($deudores as $item) {
                    $ci_per = strtoupper($item['id']);
                    $des_per = strtoupper($item['des_per']);
                    $des_per1 = strtoupper($item['des_per1']);
                    $data = Solvency::UpdateDebtorDocument($id_documento, $gestion, $fecha, $ci_per, $des_per, $des_per1, $referencia, $cod_prg, $des_prg, $usr_cre, $tipo, $id_persona);                    
                }
                return json_encode($id_documento);*/
                break;
            default:
                break;
        }
        return json_encode($id_documento);
    }


        //  * EF3. Guarda los documentos digitalizados
    public function storeDigitalDocumentSolvency(Request $request)
    {
        $id = $request->get('idx');
        $description = $request->get('referencia');
        $fecha = $request->get('fecha');
        $fileExt = $request->file('file')->getClientOriginalExtension();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data = file_get_contents($file);
            $escaped = pg_escape_bytea($data);
            $data = Solvency::StoreDigitalDocumentSolvency($id, $description, $fecha, $escaped);
        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        return response()->json(['success' => 'Uploaded Successfully.']);
    }

    //  * EF4. Obtener documentos digitalizados
    public function getDigitalFinancialDocument(Request $request)
    {
        $id = $request->get('id');
        $year = $request->get('year');
        $result = Document::getDigitalFinancialDocument($id, $year);
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
