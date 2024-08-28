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
        $gestion = $request->get('year');
        $dataBudgetItem= FixedAsset::GetBudgetItem($gestion);
        $dataAccountingItem = FixedAsset::GetAccountingItem($gestion);
        $dataMeasurement = FixedAsset::GetMeasurement($gestion);
        return json_encode(['dataBudgetItem' => $dataBudgetItem, 'dataAccountingItem' => $dataAccountingItem,'dataMeasurement' => $dataMeasurement]);
    }

    public function storeDataRegularize(Request $request)
    {
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


}
