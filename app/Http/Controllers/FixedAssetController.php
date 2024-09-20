<?php

namespace App\Http\Controllers;

use App\FixedAsset;
use App\Libraries\JSRClient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class FixedAssetController extends Controller
{
    //

    //  * Obtener una lista de documentos de entrega de el recurso utilizado.
    //  * {year: año , type: tipo del documento}
    public function getDocumentFixedAssetByYear(Request $request)
    {
        $year = $request->get('year');
        $type = 1;
        $data = FixedAsset::GetDocumentFixedAssetByYear($year, $type);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/documentFixedAssets')]
        );
        return json_encode($paginate);
    }

    //  * Obtener una lista de documentos de entrega de el recurso utilizado.
    //  * {year: año , type: tipo del documento}
    public function getFixedAssetsbyDocument(Request $request)
    {
        $document = $request->get('id');
        $data = FixedAsset::getFixedAssetsbyDocument($document);
        return json_encode($data);
    }

    //reporte para la impresion de ticketes de activos fijos
    public function getReportSelectedFixedAssets(Request $request)
    {
        $lista = $request->get('lista');
        $nreport = $request->get('reporte');

        $lista = implode(',', $lista);
        $controls = array('p_lista' => $lista);
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    public function getReportSelectedFixedAssets2(Request $request)
    {
        $nreport = 'valores';
        $report = JSRClient::GetReport($nreport);
        return $report;
    }

    public function getCategoryProgramatic(Request $request)
    {
        $year = $request->get('year');
        $descripcion = strtoupper($request->get('description'));
        $data = FixedAsset::GetCategoryProgramatic($year, $descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getCategoryProgramatic')]
        );
        return json_encode($paginate);
    }

    public function getFixedAssetsDetails(Request $request)
    {
        $id = $request->get('id');
        $tipo = $request->get('typea');
        $gestion = $request->get('year');
        $dataDocument = FixedAsset::GetAssignmentsById($id, $tipo, $gestion);
        $dataBudgetItem= FixedAsset::GetBudgetItem($gestion);
        $dataAccountingItem = FixedAsset::GetAccountingItem($gestion);
        $dataMeasurement = FixedAsset::GetMeasurement($gestion);
        return json_encode(['dataDocument' => $dataDocument, 'dataBudgetItem' => $dataBudgetItem, 'dataAccountingItem' => $dataAccountingItem,'dataMeasurement' => $dataMeasurement]);
    }
    
    public function getSearchFixedAssets(Request $request)
    {
        $descripcion = strtoupper($request->get('description'));
        $gestion = $request->get('year');
        $dataSearched = FixedAsset::GetSearchFixedAssets($descripcion, $gestion);
        return json_encode(['dataSearched' => $dataSearched]);
    }

    public function storeDataRegularize(Request $request)
    {

        /*
        codigo VARCHAR(20),
        codigo_anterior VARCHAR(20),
        des_general VARCHAR,
        des_detallada TEXT DEFAULT ''::text,
        medida VARCHAR,
        cantidad INTEGER,
        importe NUMERIC(12,2),
        fecha_adquisicion DATE,
        id_contable INTEGER,
        id_presupuesto INTEGER,
        estado VARCHAR(20) DEFAULT ''::character varying,
        cod_prg VARCHAR(8),
        des_prg VARCHAR,
        ci_resp VARCHAR,
        id_regularizacion INTEGER
        */

        $documento = $request->get('documento');
        $des_general = strtoupper($documento['des_general']);
        $des_detallada = strtoupper($documento['des_detallada']);
        $medida = strtoupper($documento['medida']);
        $cantidad = strtoupper($programa['cantidad']);
        $importe = strtoupper($programa['importe']);
        $fecha_adquisicion = strtoupper($responsable['fecha_adquisicion']);
        $id_contable = strtoupper($responsable['id_contable']);
        $id_presupuesto = strtoupper($responsable['id_presupuesto']);
        $estado = strtoupper($responsable['estado']);
        $cod_prg = strtoupper($responsable['cod_prg']);
        $des_prg = strtoupper($responsable['des_prg']);
        $ci_resp = strtoupper($responsable['ci_resp']);

        $usuario = $request->get('usuario');

        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $id = FixedAsset::AddRegularizeDocument($des_general, $fecha_adquisicion, $cod_prg, $des_prg);
                $id_documento = $id[0]->{'ff_registrar_documento'};
                //  * Deudores
                foreach ($deudores as $item) {
                    # code...
                    $id = FixedAsset::AddRegularizeFixedAssets($des_general, $des_detallada, $medida, $cantidad, $importe, $fecha_adquisicion, $id_contable, $id_presupuesto, $estado, $cod_prg, $des_prg, $ci_per);
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


    //  *  AC1. Obtiene la lista de categorias programaticas
    //  * {year: gestion en la que se desarrolla}
    public function getDataPrograms(Request $request){
        $gestion = $request->get('year');
        \Log::info("Ingresa aca");
        $data = FixedAsset::GetDataPrograms($gestion);
        return json_encode($data);
    }

    //  *  AC2. Obtiene la lista de asignaciones
    //  * {year: gestion en la que se desarrolla}
    public function getAssignments(Request $request){
        $descripcion = $request->get('description');
        $tipo = 0;
        $gestion = $request->get('year');
        $data = FixedAsset::GetAssignments($descripcion, $tipo, $gestion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 30;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getAssignments')]
        );
        return json_encode($paginate);
    }
    //  * AC3. Guardar la nueva asignacion
    public function storeAssignments(Request $request)
    {

        $documento = $request->get('document');
        $usuario = $request->get('user');
        $tipo = 0;
        $fecha = $documento['fecha'];
        $cod_prg = strtoupper($documento['cod_prg']);
        $des_prg = strtoupper($documento['des_prg']);
        $ci_resp = strtoupper($usuario['nodip']);
        $ci_elab = strtoupper($usuario['nodip']);
        $usuario = $usuario['usuario'];
        $gestion = 2024;//$usuario['gestion'];

        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $id = FixedAsset::StoreAssignments($tipo, $fecha, $cod_prg, $des_prg, $ci_resp, $ci_elab, $usuario, $gestion);
                $id_documento = $id[0]->{'ff_registrar_asignacion'};
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

}
