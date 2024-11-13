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

    //reporte para la impresion de ticketes de activos fijos
    public function reportSelectedFixedAssets2(Request $request)
    {
        $lista = $request->get('lista');
        $nreport = $request->get('reporte');

        $lista = implode(',', $lista);
        $controls = array('p_lista' => $lista);
        \Log::info($request);
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
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
        $dataBudgetItem = FixedAsset::GetBudgetItem($gestion);
        $dataAccountingItem = FixedAsset::GetAccountingItem($gestion);
        $dataMeasurement = FixedAsset::GetMeasurement($gestion);
        $dataFixedAssets = FixedAsset::GetFixedAssetsAssignment($id);
        $dataAditional = FixedAsset::GetAditionalFixedAssetsAssignment($id);
        return json_encode(['dataDocument' => $dataDocument, 'dataBudgetItem' => $dataBudgetItem, 'dataAccountingItem' => $dataAccountingItem, 'dataMeasurement' => $dataMeasurement, 'dataFixedAssets' => $dataFixedAssets, 'dataAditional' => $dataAditional]);
    }

    //  *  AC8. Obtiene la lista de asignaciones detallado por activo
    public function getFixedAssetsDetailsById(Request $request)
    {
        $id = $request->get('id');
        $gestion = $request->get('year');
        $dataFixedAssets = FixedAsset::GetFixedAssetsById($id, $gestion);
        $dataAditional = FixedAsset::GetAditionalFixedAssetsById($id);
        return json_encode(['dataFixedAssets' => $dataFixedAssets, 'dataAditional' => $dataAditional]);
    }

    public function getSearchFixedAssets(Request $request)
    {
        $descripcion = strtoupper($request->get('description'));
        $gestion = $request->get('year');
        $dataSearched = FixedAsset::GetSearchFixedAssets($descripcion, $gestion);
        return json_encode(['dataSearched' => $dataSearched]);
    }

    public function storeActiveFixed2(Request $request)
    {
        $id_activo = 0;
        $id_adicional = 0;
        $usuario = $request->get('usuario');
        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'regularizar':
                $documento = $request->get('document');
                $activos = $request->get('fixedAsset');
                $regulares = $request->get('editFixedAssets');
                $adicional = $activos['aditional'];

                $idx = 1;
                $medida = strtoupper($activos['medida']);
                $cantidad = 1;
                $id_contable = strtoupper($activos['id_contable']);
                $id_presupuesto = strtoupper($activos['id_presupuesto']);
                $estado = strtoupper($activos['estado']);
                $cod_prg = strtoupper($documento['cod_prg']);
                $des_prg = strtoupper($documento['des_prg']);
                $ci_resp = strtoupper($documento['ci_resp']);
                $id_asignaciones = strtoupper($documento['id']);

                for ($i = 0; $i < count($regulares); $i++) {
                    # code...
                    $codigo = 'codigo';
                    $codigo_anterior = strtoupper($regulares[$i]['codigo']);
                    $descripcion = strtoupper($activos['descripcion']);
                    $medida = strtoupper($activos['medida']);
                    $importe = strtoupper($activos['importe']);
                    $fecha_adquisicion = $activos['fecha_adquisicion'];
                    $idea = FixedAsset::StoreActiveFixed2(
                        $codigo,
                        $codigo_anterior,
                        $idx,
                        $descripcion,
                        $medida,
                        $cantidad,
                        $importe,
                        $fecha_adquisicion,
                        $id_contable,
                        $id_presupuesto,
                        $estado,
                        $cod_prg,
                        $des_prg,
                        $ci_resp,
                        $id_asignaciones
                    );
                    $idx = $idx + 1;
                    $id_activo = $idea[0]->{'ff_registrar_activos'};
                    foreach ($adicional as $item) {
                        # code...
                        $a_cantidad = $item['cantidad'];
                        $a_descripcion = strtoupper($item['descripcion']);
                        $a_serial = '';

                        $ideb = FixedAsset::StoreActiveFixedAditional(
                            $a_cantidad,
                            $a_descripcion,
                            $a_serial,
                            $id_activo
                        );
                        $id_adicional = $ideb[0]->{'id'};
                    }
                }
                return json_encode($id_activo);
                break;
            case 'registrar':
                $documento = $request->get('document');
                $activos = $request->get('fixedAsset');
                $adicional = $activos['aditional'];
                $idx = 1;
                $medida = strtoupper($activos['medida']);
                $cantidad = 1;
                $id_contable = strtoupper($activos['id_contable']);
                $id_presupuesto = strtoupper($activos['id_presupuesto']);
                $estado = strtoupper($activos['estado']);
                $cod_prg = strtoupper($documento['cod_prg']);
                $des_prg = strtoupper($documento['des_prg']);
                $ci_resp = strtoupper($documento['ci_resp']);
                $id_asignaciones = strtoupper($documento['id']);

                for ($i = 0; $i < $activos['cantidad']; $i++) {
                    # code...
                    $codigo = 'codigo';
                    $codigo_anterior = 0;
                    $descripcion = strtoupper($activos['descripcion']);
                    $medida = strtoupper($activos['medida']);
                    $importe = strtoupper($activos['importe']);
                    $fecha_adquisicion = $activos['fecha_adquisicion'];
                    $idea = FixedAsset::StoreActiveFixed2(
                        $codigo,
                        $codigo_anterior,
                        $idx,
                        $descripcion,
                        $medida,
                        $cantidad,
                        $importe,
                        $fecha_adquisicion,
                        $id_contable,
                        $id_presupuesto,
                        $estado,
                        $cod_prg,
                        $des_prg,
                        $ci_resp,
                        $id_asignaciones
                    );
                    $idx = $idx + 1;
                    $id_activo = $idea[0]->{'ff_registrar_activos'};
                    foreach ($adicional as $item) {
                        # code...
                        $a_cantidad = $item['cantidad'];
                        $a_descripcion = strtoupper($item['descripcion']);
                        $a_serial = '';
                        $ideb = FixedAsset::StoreActiveFixedAditional(
                            $a_cantidad,
                            $a_descripcion,
                            $a_serial,
                            $id_activo
                        );
                        $id_adicional = $ideb[0]->{'id'};
                    }
                }
                return json_encode($id_activo);
                break;
            default:
                break;
        }
        return json_encode($id_activo);
    }
    //  *  AC1. Obtiene la lista de categorias programaticas
    //  * {year: gestion en la que se desarrolla}
    public function getDataPrograms(Request $request)
    {
        $gestion = $request->get('year');
        \Log::info("Ingresa aca");
        $data = FixedAsset::GetDataPrograms($gestion);
        return json_encode($data);
    }

    //  *  AC2. Obtiene la lista de asignaciones
    //  * {year: gestion en la que se desarrolla}
    public function getAssignments(Request $request)
    {
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
        $gestion = 2024; //$usuario['gestion'];

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
