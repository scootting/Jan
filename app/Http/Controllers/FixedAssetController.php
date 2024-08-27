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
}
