<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Libraries\JSRClient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryController extends Controller
{

    //reportes general y detallado de activos fijos usando JasperServer
    public function getReport(Request $request)
    {
        $tip_repo = $request->get('reporte');
        $ofc_cod = $request->get('ofc_cod');
        $tipo_filtro = $request->get('filtroTipo');
        $valor = $request->get('filtroValor');
        if ($tip_repo == 'general') {
            switch ($tipo_filtro) {
                case 'cargo':
                    $controls = array('p_car_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'car_general_1'; //funciona
                    break;
                case 'subUnidad':
                    $controls = array('p_sub_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'sub_ofc_general_1'; //funciona
                    break;
                    $controls = array('p_resp_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'resp_general_1'; //funciona
                    break;
                case 'responsable':
                    break;
                case 'todo':
                    $controls = array('p_unidad' => $ofc_cod);
                    $reportName = 'todo_general'; //funciona
                    break;
            }
            $report = JSRClient::GetReportWithParameters($reportName, $controls);
            return $report;
        } else { //detallado
            switch ($tipo_filtro) {
                case 'cargo':
                    $controls = array('p_car_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'car_detallado_1'; //funciona
                    break;
                case 'subUnidad':
                    $controls = array('p_sub_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'sub_ofc_detallado_1'; //funciona
                    break;
                case 'responsable':
                    $controls = array('p_resp_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'resp_detallado_1'; //funciona
                    break;
                case 'todo':
                    $controls = array('p_unidad' => $ofc_cod);
                    $reportName = 'todo_detallado_1'; //funciona
                    break;
            }
            $report = JSRClient::GetReportWithParameters($reportName, $controls);
            return $report;
        }
    }

    public function getReportXML(Request $request)
    {
        $tip_repo = $request->get('reporte');
        $ofc_cod = $request->get('ofc_cod');
        $tipo_filtro = $request->get('filtroTipo');
        $valor = $request->get('filtroValor');
        if ($tip_repo == 'general') {
            switch ($tipo_filtro) {
                case 'cargo':
                    $controls = array('p_car_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'car_general_1'; //funciona
                    break;
                case 'subUnidad':
                    $controls = array('p_sub_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'sub_ofc_general_1'; //funciona
                    break;
                    $controls = array('p_resp_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'resp_general_1'; //funciona
                    break;
                case 'responsable':
                    break;
                case 'todo':
                    $controls = array('p_unidad' => $ofc_cod);
                    $reportName = 'todo_general'; //funciona
                    \Log::info("Inventario Todo");
                    break;
            }
            $report = JSRClient::GetReportWithParametersXLS($reportName, $controls);
            return $report;
        } else { //detallado
            switch ($tipo_filtro) {
                case 'cargo':
                    $controls = array('p_car_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'car_detallado_1'; //funciona
                    break;
                case 'subUnidad':
                    $controls = array('p_sub_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'sub_ofc_detallado_1'; //funciona
                    break;
                case 'responsable':
                    $controls = array('p_resp_unidad' => implode(',', $valor), 'p_unidad' => $ofc_cod);
                    $reportName = 'resp_detallado_1'; //funciona
                    break;
                case 'todo':
                    $controls = array('p_unidad' => $ofc_cod);
                    $reportName = 'todo_detallado_1'; //funciona
                    \Log::info("Inventario Detallado");
                    break;
            }
            $report = JSRClient::GetReportWithParametersXLS($reportName, $controls);
            return $report;
        }
    }    
    //  * 1. Obtener una lista de inventarios por usuario de el recurso utilizado.
    //  * {year: año , user: usuario que esta creando el inventario}
    //
    public function getInventories(Request $request)
    {
        $usuario = $request->get('user');
        $gestion = $request->get('year');
        \Log::info("esta es la pagina que usamos: " . $request->get('page'));
        //$descripcion = $request->get('description');
        $data = Inventory::getInventories($gestion, $usuario);

        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory2')]
        );
        return json_encode($paginate);
    }

    //  * 2. Imprimir el reporte del inventario general o detallado de el recurso utilizado.
    //  * {document: el numero de la solicitud year: la gestion, report: nombre del reporte}
    //
    public function inventoryReport(Request $request)
    {
        $oficina = $request->get('office');
        $documento = $request->get('document');
        $gestion = $request->get('year');
        $nreport = $request->get('report');

        $controls = array(
            'p_oficina' => $oficina,
            'p_documento' => $documento,
            'p_gestion' => $gestion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * 3. Obtener una lista de activos fijos para el inventario utilizado.
    //  * {id_inventory: el numero del inventario year: la gestion}
    //
    public function getActivesByInventory(Request $request)
    {
        $inventario = $request->get('id_inventory');
        $gestion = $request->get('year');
        $data = Inventory::getActivesByInventory($inventario, $gestion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 20;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getActivesByInventory')]
        );
        return json_encode($paginate);
    }

    //  * 4. Obtener una lista de estados por cada activo fijo utilizado.
    //  * {}
    //
    public function getStatesByActive()
    {
        $data = Inventory::getStatesByActive();
        return json_encode($data);
    }

    //  * I5. Guardar los detalles determinados para cada activo fijo del inventario.
    //  * {activedetail: son los detalles efectuados al activo del inventario}
    //
    public function storeActiveDetail(Request $request)
    {
        $marca = $request->get('marker'); //actualizar o editar
        $inventario = $request->get('id'); //id del inventario
        $gestion = $request->get('year'); //gestion del inventario

        $activeDetail = $request->get('activeDetail');

        $idActive = $activeDetail['id_act']; //id del activo en su tabla correspondiente
        $tableActive = $activeDetail['per_tab']; //pertenencia del activo a la tabla que corresponda

        $validationActive = $activeDetail['validacion']; //existencia del activo fijo
        $validationActive = ($validationActive != '1' ? 0 : 1);
        $stateActive = $activeDetail['est_act']; //estado actual del activo
        $observationActive = $activeDetail['obs_est']; //observaciones al activo
        $saveActive = "true"; //confirmar el guardado del detalle del activo
        $storeActive = "Verificado"; //cambiando de solicitado a verificado

        $marker = Inventory::StoreActiveDetail($inventario, $gestion, $idActive, $tableActive, $validationActive, $stateActive, $observationActive, $saveActive, $storeActive);
        return json_encode($marker);
    }

    public function getOffices(Request $request, $gestion)
    {
        $descripcion = ($request->get('descripcion') ? $request->get('descripcion') : '');
        $data = Inventory::getOffices($gestion, $descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory')]
        );
        return json_encode($paginate);
    }
    public function getOfficeByCodSoa($cod_soa)
    {
        $data = Inventory::getOfficeByCodSoa($cod_soa);
        $data->so_cargos = Inventory::getDatosByCodSoa($cod_soa);
        return json_encode($data);
    }
    public function getSubOfficesByCodSoa($cod_soa)
    {
        $cod_soa = $cod_soa == 'null' ? null : $cod_soa;
        $data = Inventory::getSubOfficesByCodSoa($cod_soa);
        return json_encode($data);
    }
    public function getCargosByCodSoa($cod_soa)
    {
        $cod_soa == 'null' ? null : $cod_soa;
        $data = Inventory::getCargorsForActive($cod_soa);
        return json_encode($data);
    }
    public function getResponsablesByCodSoa($cod_soa)
    {
        $cod_soa == 'null' ? null : $cod_soa;
        $data = Inventory::getResponsablesForActive($cod_soa);
        return json_encode($data);
    }
    //obtener activos para la lista en la página de Inventarios (ACTIVOS POR UNIDAD).
    public function getActivosByFilter(Request $request, $cod_soa)
    {
        $tipo_reporte = ($request->get('reporte'));
        $tipo_filtro = ($request->get('filtroTipo'));
        $valor = ($request->get('filtroValor'));
        $cod = ($request->get('descripcion') ? $request->get('descripcion') : '');
        $data = [];
        switch ($tipo_filtro) {
            case 'cargo':
                $data = Inventory::selectByCargo($tipo_reporte, $cod_soa, $valor, $cod);
                break;
            case 'subUnidad':
                $data = Inventory::selectBysubUnidad($tipo_reporte, $cod_soa, $valor, $cod);
                break;
            case 'responsable':
                $data = Inventory::selectByCiResponsable($tipo_reporte, $cod_soa, $valor, $cod);
                break;
            case 'todo':
                $data = Inventory::getActivosBySoa($tipo_reporte, $cod_soa, $cod);
                break;
        }
        $page = ($request->get('page')) ? $request->get('page') : null;
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory/activos/' . $cod_soa)]
        );
        return json_encode($paginate);
    }
    //USADO EN ACTIVOS,EDIT ACTIVOS Y EDIT-INVENTORY
    public function getUnidad(Request $request)
    {
        $keyWord = ($request->get('keyWord') ? $request->get('keyWord') : '');
        $data = Inventory::getUnidad($keyWord);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory2/unidad')]
        );
        return json_encode($paginate);
    }
    public function getSubUnidad(Request $request)
    {
        $unidad = ($request->get('cod_soa') ? $request->get('cod_soa') : '');
        $idoffice = ($request->get('idoffice') ? $request->get('idoffice') : '');
        $cod_ofc = ($request->get('cod_ofc') ? $request->get('cod_ofc') : '');
        $data = Inventory::getSubUnidades($unidad, $idoffice, $cod_ofc);
        return json_encode($data);
    }
    public function getCargos(Request $request)
    {
        $unidad = ($request->get('cod_soa') ? $request->get('cod_soa') : '');
        $sub_unidades = ($request->get('sub_unidades') ? $request->get('sub_unidades') : []);
        $data = Inventory::getCargos($unidad, $sub_unidades);
        return json_encode($data);
    }
    public function getResponsables(Request $request)
    {
        //dd($request);
        $unidad = ($request->get('unidad') ? $request->get('unidad') : '');
        $cargos = ($request->get('cargos') ? $request->get('cargos') : []);
        $sub_unidades = ($request->get('sub_uni') ? $request->get('sub_uni') : []);
        $data = Inventory::getResponsables($unidad, $cargos, $sub_unidades);
        return json_encode($data);
    }
    //esto es para inventarios (normal) que ya se tiene listado.
    public function getResponsablesByUnidad(Request $request)
    {
        $unidad = ($request->get('unidad') ? $request->get('unidad') : '');
        $data = Inventory::getResponsablesbyUnidad($unidad);
        return json_encode($data);
    }
    public function getEncargados(Request $request)
    {
        $nro_dip = ($request->get('nro_dip') ? $request->get('nro_dip') : '');
        $data = Inventory::getEncargados($nro_dip);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/inventory2/encargados')]
        );
        return json_encode($paginate);
    }
    public function saveNewInventory(Request $request)
    {
        $no_doc = $request->no_doc;
        $res_enc = $request->encargados;
        $car_cod_enc = [];
        for ($i = 0; $i < count([$res_enc]); $i++) {
            $car_cod_enc[] = 3;
        }
        $car_cod = $car_cod_enc;
        $ofc_cod = $request->unidad;
        $sub_ofc_cod = $request->subUnidades;
        //$car_cod_resp = $request->cargos;
        //$ci_res = $request->responsables;
        $estado = 'ELABORADO';
        $gestion = '2021'; // $request->gestion;
        $entregado = $request->per_inv;
        $en_car = $request->car_per;
        $recibido = $request->new_per;
        $re_car = $request->new_car_per;
        $superior = $request->get('sup') ? $request->get('sup') : ['0'];
        $sup_car = $request->get('car_per_sup') ? $request->get('car_per_sup') : '0';
        $data = Inventory::saveNewInventory($no_doc, $res_enc, $car_cod, $ofc_cod,
            $sub_ofc_cod, $estado, $gestion, $entregado, $en_car, $recibido, $re_car, $superior, $sup_car);
        return json_encode($data);
    }
    public function saveDatasDetail(Request $request)
    {
        //dd($request);
        $no_doc = $request->no_doc;
        $ofc_cod = $request->ofc_cod;
        $sub_ofc_cod = $request->sub_ofc_cod;
        $gestion = '2021';
        $validacion = 'false';
        $data = Inventory::saveActivesToNewInventory($no_doc, $ofc_cod, $sub_ofc_cod, $gestion, $validacion);
        return json_encode($data);
    }
    public function getDocDetailByActivoId($id)
    {
        $data = Inventory::getDocDetailByActivoId($id);
        return json_encode($data);
    }
    public function getActive($id)
    {
        $data = Inventory::showActiveById($id);
        return json_encode($data);
    }
    public function getInventory($id)
    {
        $data = Inventory::showInventoryById($id);
        return json_encode($data);
    }
    public function saveChangeActive(Request $request)
    {
        //dd($request);
        $cod_soa = $request->cod_soa;
        $des = $request->des;
        $des_det = $request->des_det;
        $vida_util = $request->vida_util;
        $car_cod = $request->car_cod;
        $estado = $request->estado;
        $ofc_cod = $request->cod_soa;
        $sub_ofc_cod = $request->sub_ofc_cod;
        $ci_resp = $request->ci_resp;
        $id = $request->id;
        $data = Inventory::saveChangeActive($cod_soa, $des, $des_det, $vida_util, $car_cod, $estado, $ofc_cod, $sub_ofc_cod, $ci_resp, $id);
        return json_encode($data);
    }
    public function saveNewActive(Request $request)
    {
        //dd($request);
        $cod_soa = $request->cod_soa;
        $des = $request->des;
        $des_det = $request->des_det;
        $par_cod = $request->partida;
        $cod_con = $request->contable;
        $car_cod = $request->car_cod;
        $estado = $request->estado;
        $sub_ofc_cod = $request->sub_ofc_cod;
        $ci_resp = $request->ci_resp;
        $nro_doc = $request->nro_doc;
        $idc = $request->cantidad;
        $imp_act = '2';
        $des_act = '1';
        $gestion = '2021';
        $uni_med = $request->med;
        $cod_ant = $request->cod_ant;
        $cod_nue = "0";
        //dd($cod_soa, $des, $des_det, $par_cod,$cod_con,$car_cod, $estado, $sub_ofc_cod, $ci_resp,$nro_doc,$idc,$imp_act,$des_act,$gestion);
        $data = Inventory::saveNewActive($cod_soa, $des, $des_det, $par_cod, $cod_con, $car_cod,
            $estado, $sub_ofc_cod, $ci_resp, $nro_doc, $idc, $imp_act, $des_act, $gestion, $uni_med, $cod_ant, $cod_nue);
        return json_encode($data);
    }
    public function saveChangeDocInventory(Request $request)
    {
        //dd($request);
        $id = $request->id;
        $res_enc = $request->res_enc;
        $car_cod_enc = [];
        for ($i = 0; $i < count([$res_enc]); $i++) {
            $car_cod_enc[] = 3;
        }
        $car_cod = $car_cod_enc;
        $ofc_cod = $request->ofc_cod;
        $sub_ofc_cod = $request->sub_ofc_cod;
        $car_cod_resp = $request->car_cod_resp;
        $ci_res = $request->ci_res;
        $data = Inventory::saveChangeDocInventory($id, $res_enc, $car_cod, $ofc_cod, $sub_ofc_cod, $car_cod_resp, $ci_res);
        return json_encode($data);
    }
    public function showDocInventory($no_cod)
    {
        $data = Inventory::showInventoryById($no_cod);
        $data->sub_oficinas = [];
        foreach ($data->sub_ofc_cod as $idso) {
            $data->sub_oficinas[] = Inventory::getSubUnidadById($idso);
        }
        $data->oficina = Inventory::getOfficeByCodSoa($data->ofc_cod);
        $data->cargos_encargados = [];
        foreach ($data->car_cod as $idc) {
            $data->cargos_encargados[] = Inventory::getCargoById($idc);
        }
        $data->encargados = [];
        foreach ($data->res_enc as $nd) {
            $data->encargados[] = Inventory::getEncargados($nd)[0];
        }
        $data->cargos_responsables = [];
        foreach ($data->car_cod_resp as $idc) {
            $data->cargos_responsables[] = Inventory::getCargoById($idc);
        }
        $data->responsables = [];
        foreach ($data->ci_res as $nd) {
            $data->responsables[] = Inventory::getEncargados($nd)[0];
        }
        return json_encode($data);
    }
    //traer la lista de activos dentro del detalle
    public function getActivesForDocInv(Request $request, $doc_cod)
    {
        $keyWord = ($request->get('keyWord')) ? $request->get('keyWord') : '';
        $data = Inventory::SearchActiveForDocInvRegistered($doc_cod, $keyWord);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/documentqr')]
        );
        return json_encode($paginate);
    }

    public function controlTrue(Request $request)
    {
        $no_cod = ($request->get('no_cod')) ? $request->get('no_cod') : '';
        $data = Inventory::controlTrue($no_cod);
        return json_encode($data);
    }
    public function getPartidas()
    {
        $data = Inventory::getPartidas();
        return json_encode($data);
    }
    public function getContable()
    {
        $data = Inventory::getContable();
        return json_encode($data);
    }
    public function getlastNroDoc()
    {
        $data = Inventory::getLastNroDoc();
        return json_encode($data);
    }
    public function changeStateInventory(Request $request)
    {
        $estado = $request->estado;
        $observaciones = $request->observaciones;
        $id = $request->nro_cod;
        $verificado = $request->verificado;
        $data = Inventory::updateState($estado, $observaciones, $verificado, $id);
        return json_encode($data);
    }
    public function getAllCargos()
    {
        $data = Inventory::getAllCargos();
        return json_encode($data);
    }
    //funcion cargar imagenes de activos para nuevo inventario
    public function uploadImage(Request $request)
    {
        //dd($request);
        $dataSource = $request->get('datasource');
        $arrayData = json_decode($dataSource, true);
        $ofc_cod = $arrayData['ofc_cod'];
        $id = $arrayData['id'];
        $sub_ofc_cod = $arrayData['sub_ofc_cod'];
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $path = 'upload/' . strval($ofc_cod) . '/' . strval($sub_ofc_cod) . '/' . strval($id);
            $file->storeAs($path, $file_name);
        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        return response()->json(['success' => 'Cargo exitoso.', 'path' => '/' . $path . '/' . $file_name]);
    }
    public function saveImages(Request $request)
    {
        $cod_act = $request->cod_act;
        $img_fro = $request->img_fro;
        $img_der = $request->img_der;
        $img_izq = $request->img_izq;
        $img_post = $request->img_post;
        $img_sup = $request->img_sup;
        $data = Inventory::saveImage($cod_act, $img_fro, $img_izq, $img_der, $img_sup, $img_post);
        return json_encode($data);
    }
    public static function getImagesById($cod_act)
    {
        $data = Inventory::getImagesByIdAct($cod_act);
        return json_encode($data);
    }
    public function getListNroDoc(Request $request)
    {
        $descripcion = ($request->get('descripcion') ? $request->get('descripcion') : '');
        $data = Inventory::getListNroDoc($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/documentqr')]
        );
        return json_encode($paginate);
    }
    public function getActivesbyDocument(Request $request)
    {
        $document = $request->get('id');
        $data = Inventory::getActivesbyDocument($document);
        return json_encode($data);
    }
    public function informeGeneral(Request $request)
    {
        $no_doc = $request->get('no_doc');
        $nreport = 'InventoryGeneral_1';
        $controls = array('p_no_doc' => $no_doc);
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
        //cambios en el servidor
    }

}
