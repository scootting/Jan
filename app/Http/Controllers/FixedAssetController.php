<?php
namespace App\Http\Controllers;

use App\FixedAsset;
use App\Libraries\JSRClient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Http;

class FixedAssetController extends Controller
{

    //  *  AF10. Obtiene la informacion necesaria para crear un documento de entrega
    public function getDataAssignment(Request $request)
    {
        $id                   = $request->get('id');
        $gestion              = $request->get('year');
        $types                = FixedAsset::GetTypesOfDocument($gestion);
        $positions            = FixedAsset::GetPositionsOfDocument($gestion);
        $categoryProgramatics = FixedAsset::GetCategoryProgramaticsOfDocument($gestion);
        return json_encode(['types' => $types, 'positions' => $positions, 'categoryProgramatics' => $categoryProgramatics]);
    }

    //  *  AF11. Obtiene la informacion necesaria para crear un documento de entrega
    public function storeDataAssignment(Request $request)
    {

        $documento = $request->get('assignment');
        $usuario   = $request->get('user');
        $marcador  = $request->get('marker');

        $fecha    = $documento['fecha'];
        $cod_tipo = $documento['cod_tipo'];
        $cod_prg  = strtoupper($documento['cod_prg']);
        $ref_prg  = strtoupper($documento['ref_prg']);
        $des_prg  = strtoupper($documento['des_prg']);
        $ci_resp  = strtoupper($documento['ci_resp']);
        $des_resp = strtoupper($documento['des_resp']);
        $id_cargo = $documento['id_cargo'];

        $ci_elab     = $usuario['nodip'];
        $des_usuario = $usuario['usuario'];
        $gestion     = $usuario['gestion'];

        $id_documento = 0;
        $marcador     = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                \Log::info("registrar");
                $id           = FixedAsset::StoreDataAssignment($cod_tipo, $fecha, $cod_prg, $des_prg, $ci_resp, $ci_elab, $des_usuario, $gestion, $des_resp, $ref_prg, $id_cargo);
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

    //  *  AF12. Obtiene la lista de documentos    
    public function GetDataAssignments(Request $request)
    {
        $gestion = $request->get('gestion');
        $tipo    = $request->get('tipo');

        $type     = 1;
        $data     = FixedAsset::GetDataAssignments($gestion);
        $page     = ($request->get('page') ? $request->get('page') : 1);
        $perPage  = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/assignments')]
        );
        return json_encode($paginate);
    }

    //  *  AF13. Obtiene la informacion necesaria para crear activos fijos dentro de un documento       
    public function getDataAssignmentDetails(Request $request)
    {
        $id                     = $request->get('id');
        $gestion                = $request->get('year');
        $dataAssignment         = FixedAsset::GetDataAssignmentsById($id, $gestion);
        $dataBudgetItem         = FixedAsset::GetBudgetItem($gestion);
        $dataAccountingItem     = FixedAsset::GetAccountingItem($gestion);
        $dataMeasurement        = FixedAsset::GetMeasurement($gestion);
        $dataAssignmentsDetails = FixedAsset::GetDataAssignmentsDetails($id);
        //$dataAditional      = FixedAsset::GetAditionalFixedAssetsAssignment($id);
        return json_encode(['dataAssignment' => $dataAssignment, 'dataBudgetItem' => $dataBudgetItem, 'dataAccountingItem' => $dataAccountingItem, 'dataMeasurement' => $dataMeasurement, 'dataAssignmentsDetails' => $dataAssignmentsDetails]);
    }

    //  *  AF13. Obtiene la informacion necesaria de un documento    
    public static function GetAssignmentsById(Request $request)
    {
        $id_asignacion = $request->get('id');
        $cod_tipo      = $request->get('cod_tipo');
        $gestion       = $request->get('gestion');
        $query         = "select * from actx.ff_datos_asignacion_id('" . $id_asignacion . "'," . $cod_tipo . ",'" . $gestion . "')";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF14. Guarda la informacion necesaria para crear activos fijos dentro de un documento       
    public function storeDataAssignmentDetails(Request $request)
    {

        $documento         = $request->get('assignment');
        $assignmentDetails = $request->get('assignmentDetails');
        $usuario           = $request->get('user');
        $marcador          = $request->get('marker');

        \Log::info($documento['id']);
        $id_documento = $documento['id'];
        $cod_tipo     = $documento['cod_tipo'];
        $cod_prg      = strtoupper($documento['cod_prg']);
        $ref_prg      = strtoupper($documento['ref_prg']);

        $ci_elab     = $usuario['nodip'];
        $des_usuario = $usuario['usuario'];
        $gestion     = $usuario['gestion'];

        $id_eliminar = FixedAsset::RemoveDataAssignmentDetails($id_documento);
        $marcador    = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $indice = 0;
                foreach ($assignmentDetails as $item) {
                    $descripcion       = strtoupper($item['descripcion']);
                    $medida            = strtoupper($item['medida']);
                    $cantidad          = $item['cantidad'];
                    $importe           = $item['importe'];
                    $fecha_adquisicion = $item['fecha_adquisicion'];
                    $id_contable       = $item['id_contable'];
                    $des_contable      = $item['des_contable'];
                    $id_presupuesto    = $item['id_presupuesto'];
                    $des_presupuesto   = $item['des_presupuesto'];
                    $estado            = $item['estado'];
                    $adicional         = json_encode($item['adicional']); //, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                                                                          //$adicional = addslashes($adicional);
                    \Log::info($adicional);
                    $indice = $indice + 1;
                    $id     = FixedAsset::StoreDataAssignmentDetails($id_documento, $indice, $descripcion, $medida, $cantidad, $importe, $fecha_adquisicion, $id_contable, $des_contable, $id_presupuesto, $des_presupuesto, $estado, $adicional, $des_usuario);
                    $id     = $id[0]->{'ff_registrar_asignacion_detallada'};
                }
                return json_encode($id);
                break;
            case 'editar':
                $id = FixedAsset::UpdateDataAssignmentDetails($id_asset, $id_documento, $indice, $descripcion, $medida, $cantidad, $importe, $fecha_adquisicion, $id_contable, $des_contable, $id_presupuesto, $des_presupuesto, $estado, $adicional, $des_usuario);
                //$data = Solvency::UpdateDebtorDocument($id, $gestion, $fecha, $detalle, $cod_prg, $des_prg, $usr_cre, $ci_resp, $ci_elab, $id_ref);
                break;
            default:
                break;
        }
        return json_encode($id);
    }

    //  *  AF15. Verificar un documento       
    public function verifyDataAssignmentDetails(Request $request)
    {
        $documento    = $request->get('assignment');
        $usuario      = $request->get('user');
        $marcador     = $request->get('marker');
        $id_documento = $documento['id'];
        $ci_resp      = $documento['ci_resp'];
        $gestion      = $documento['gestion'];

        \Log::info($id_documento);
        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'verificar':
                $id           = FixedAsset::VerifyDataAssignmentDetails($id_documento, $ci_resp, $gestion);
                $id_documento = $id[0]->{'ff_verificar_asignacion'};
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

    //  *  AF16. Obtiene los activos registrados dentro de un documento       
    public function getDataFixedAsssetDetails(Request $request)
    {
        $id                     = $request->get('id');
        $gestion                = $request->get('year');
        $dataAssignment         = FixedAsset::GetDataAssignmentsById($id, $gestion);
        $dataBudgetItem         = FixedAsset::GetBudgetItem($gestion);
        $dataAccountingItem     = FixedAsset::GetAccountingItem($gestion);
        $dataMeasurement        = FixedAsset::GetMeasurement($gestion);
        $dataAssignmentsDetails = FixedAsset::GetDataFixedAsssetDetails($id);
        //$dataAditional      = FixedAsset::GetAditionalFixedAssetsAssignment($id);
        return json_encode(['dataAssignment' => $dataAssignment, 'dataBudgetItem' => $dataBudgetItem, 'dataAccountingItem' => $dataAccountingItem, 'dataMeasurement' => $dataMeasurement, 'dataAssignmentsDetails' => $dataAssignmentsDetails]);
    }

    //  *  AF17. imprimir los activos registrados dentro de un documento       
    public function printDataAssignmentDetails(Request $request)
    {
        $lista   = $request->get('lista');
        $nreport = $request->get('reporte');

        $lista    = implode(',', $lista);
        $controls = ['p_lista' => $lista];
        $report   = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  *  AF18. imprimir reporte de un documento de compra       
    public function printPurchaseAssignment(Request $request)
    {
        $id      = $request->get('id');
        $nreport = $request->get('reporte');

        $controls = ['p_id' => $id];
        $report   = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * Obtener una lista de documentos de entrega de el recurso utilizado.
    //  * {year: año , type: tipo del documento}
    public function getDocumentFixedAssetByYear(Request $request)
    {
        $year     = $request->get('year');
        $type     = 1;
        $data     = FixedAsset::GetDocumentFixedAssetByYear($year, $type);
        $page     = ($request->get('page') ? $request->get('page') : 1);
        $perPage  = 10;
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
        $data     = FixedAsset::getFixedAssetsbyDocument($document);
        return json_encode($data);
    }

    //reporte para la impresion de ticketes de activos fijos
    public function getReportSelectedFixedAssets(Request $request)
    {
        $lista   = $request->get('lista');
        $nreport = $request->get('reporte');

        $lista    = implode(',', $lista);
        $controls = ['p_lista' => $lista];
        $report   = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //reporte para la impresion de ticketes de activos fijos
    public function reportSelectedFixedAssets2(Request $request)
    {
        $lista   = $request->get('lista');
        $nreport = $request->get('reporte');

        $lista    = implode(',', $lista);
        $controls = ['p_lista' => $lista];
        \Log::info($request);
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    public function getCategoryProgramatic(Request $request)
    {
        $year        = $request->get('year');
        $descripcion = strtoupper($request->get('description'));
        $data        = FixedAsset::GetCategoryProgramatic($year, $descripcion);
        $page        = ($request->get('page') ? $request->get('page') : 1);
        $perPage     = 10;
        $paginate    = new LengthAwarePaginator(
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
        $id                 = $request->get('id');
        $tipo               = $request->get('typea');
        $gestion            = $request->get('year');
        $dataDocument       = FixedAsset::GetAssignmentsById($id, $tipo, $gestion);
        $dataBudgetItem     = FixedAsset::GetBudgetItem($gestion);
        $dataAccountingItem = FixedAsset::GetAccountingItem($gestion);
        $dataMeasurement    = FixedAsset::GetMeasurement($gestion);
        $dataFixedAssets    = FixedAsset::GetFixedAssetsAssignment($id);
        $dataAditional      = FixedAsset::GetAditionalFixedAssetsAssignment($id);
        return json_encode(['dataDocument' => $dataDocument, 'dataBudgetItem' => $dataBudgetItem, 'dataAccountingItem' => $dataAccountingItem, 'dataMeasurement' => $dataMeasurement, 'dataFixedAssets' => $dataFixedAssets, 'dataAditional' => $dataAditional]);
    }

    //  *  AC8. Obtiene la lista de asignaciones detallado por activo
    public function getFixedAssetsDetailsById(Request $request)
    {
        $id              = $request->get('id');
        $gestion         = $request->get('year');
        $dataFixedAssets = FixedAsset::GetFixedAssetsById($id, $gestion);
        $dataAditional   = FixedAsset::GetAditionalFixedAssetsById($id);
        return json_encode(['dataFixedAssets' => $dataFixedAssets, 'dataAditional' => $dataAditional]);
    }

    public function getSearchFixedAssets(Request $request)
    {
        $descripcion  = strtoupper($request->get('description'));
        $gestion      = $request->get('year');
        $dataSearched = FixedAsset::GetSearchFixedAssets($descripcion, $gestion);
        return json_encode(['dataSearched' => $dataSearched]);
    }

    public function storeActiveFixed2(Request $request)
    {
        $id_activo    = 0;
        $id_adicional = 0;
        $usuario      = $request->get('usuario');
        $marcador     = $request->get('marker');
        switch ($marcador) {
            case 'regularizar':
                $documento = $request->get('document');
                $activos   = $request->get('fixedAsset');
                $regulares = $request->get('editFixedAssets');
                $adicional = $activos['aditional'];

                $idx             = 1;
                $medida          = strtoupper($activos['medida']);
                $cantidad        = 1;
                $id_contable     = strtoupper($activos['id_contable']);
                $id_presupuesto  = strtoupper($activos['id_presupuesto']);
                $estado          = strtoupper($activos['estado']);
                $cod_prg         = strtoupper($documento['cod_prg']);
                $des_prg         = strtoupper($documento['des_prg']);
                $ci_resp         = strtoupper($documento['ci_resp']);
                $id_asignaciones = strtoupper($documento['id']);

                for ($i = 0; $i < count($regulares); $i++) {
                    # code...
                    $codigo            = 'codigo';
                    $codigo_anterior   = strtoupper($regulares[$i]['codigo']);
                    $descripcion       = strtoupper($activos['descripcion']);
                    $medida            = strtoupper($activos['medida']);
                    $importe           = strtoupper($activos['importe']);
                    $fecha_adquisicion = $activos['fecha_adquisicion'];
                    $idea              = FixedAsset::StoreActiveFixed2(
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
                    $idx       = $idx + 1;
                    $id_activo = $idea[0]->{'ff_registrar_activos'};
                    foreach ($adicional as $item) {
                        # code...
                        $a_cantidad    = $item['cantidad'];
                        $a_descripcion = strtoupper($item['descripcion']);
                        $a_serial      = '';

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
                $documento       = $request->get('document');
                $activos         = $request->get('fixedAsset');
                $adicional       = $activos['aditional'];
                $idx             = 1;
                $medida          = strtoupper($activos['medida']);
                $cantidad        = 1;
                $id_contable     = strtoupper($activos['id_contable']);
                $id_presupuesto  = strtoupper($activos['id_presupuesto']);
                $estado          = strtoupper($activos['estado']);
                $cod_prg         = strtoupper($documento['cod_prg']);
                $des_prg         = strtoupper($documento['des_prg']);
                $ci_resp         = strtoupper($documento['ci_resp']);
                $id_asignaciones = strtoupper($documento['id']);

                for ($i = 0; $i < $activos['cantidad']; $i++) {
                    # code...
                    $codigo            = 'codigo';
                    $codigo_anterior   = 0;
                    $descripcion       = strtoupper($activos['descripcion']);
                    $medida            = strtoupper($activos['medida']);
                    $importe           = strtoupper($activos['importe']);
                    $fecha_adquisicion = $activos['fecha_adquisicion'];
                    $idea              = FixedAsset::StoreActiveFixed2(
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
                    $idx       = $idx + 1;
                    $id_activo = $idea[0]->{'ff_registrar_activos'};
                    foreach ($adicional as $item) {
                        # code...
                        $a_cantidad    = $item['cantidad'];
                        $a_descripcion = strtoupper($item['descripcion']);
                        $a_serial      = '';
                        $ideb          = FixedAsset::StoreActiveFixedAditional(
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
        $tipo        = 'C';
        $gestion     = $request->get('year');
        $data        = FixedAsset::GetAssignments($descripcion, $tipo, $gestion);
        $page        = ($request->get('page') ? $request->get('page') : 1);
        $perPage     = 30;
        $paginate    = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getAssignments')]
        );
        return json_encode($paginate);
    }

    //  *  AF20. Obtiene un activo fijo de la lista de actualizaciones y depreciaciones
    public function getDataFixedAssetDetails(Request $request)
    {
        $descripcion            = strtoupper($request->get('description'));
        $gestion                = $request->get('year');
        $gestion                = '2024';
        $dataFixedAssetRevalued = FixedAsset::GetDataFixedAssetDetails($descripcion, $gestion);
        return json_encode(['dataFixedAssetRevalued' => $dataFixedAssetRevalued]);
    }
    //  * AF27. Obtiene la lista de imagenes que pertenecen a un activo
    public function getImagenByRevaluedAssignment(Request $request)
    {
        $id      = $request->get('id');
        $gestion = $request->get('gestion');
        return json_encode($id);
    }

    //  * EF3. Guarda los documentos digitalizados
    public function storeDigitalDocument(Request $request)
    {
        $id          = $request->get('id');
        $description = $request->get('descripcion');
        $fileExt     = $request->file('file')->getClientOriginalExtension();
        if ($request->hasFile('file')) {
            $file    = $request->file('file');
            $data    = file_get_contents($file);
            $escaped = pg_escape_bytea($data);
            $data    = FixedAsset::StoreDigitalDocument($id, $description, $escaped);
        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        return response()->json(['success' => 'Uploaded Successfully.']);
    }

    //  *  AF32. Eliminar la imagen subida del activo fijo.
    public function deleteDigitalAssetById(Request $request){
        $id = $request->get('id');
        $data  = FixedAsset::DeleteDigitalAssetById($id);
        return json_encode($data);
    }

    //  * AF31. Obtiene el documento digital seleccionado
    public function getDigitalAssetById(Request $request)
    {
        $id = $request->get('id');
        $result = FixedAsset::GetDigitalAssetById($id);
        if (!empty($result[0]->pdf_data)) {
            $my_bytea = stream_get_contents($result[0]->pdf_data);
            return $my_bytea;
        } else {
            return response()->json([
                'error' => 'No se encontró ningún registro con el ID proporcionado.',
            ]);
        }
    }



    //  *  AF30. Guardar los analisis del auditor despues de su evaluacion.
    //Route::post('storeDataAuditorDetails/', 'FixedAssetController@storeDataAuditorDetails');
    public function storeDataAuditorDetails(Request $request)
    {
        \Log::info($request);
        $dataAuditor = $request->get('dataAuditor');
        $id          = $request->get('id');
        $usuario     = $request->get('user');
        $marcador    = $request->get('marker');

        $actualizacion = $dataAuditor['actualizacion'];
        $seguridad     = $dataAuditor['seguridad'];
        $proteccion    = $dataAuditor['proteccion'];
        $capacitacion  = $dataAuditor['capacitacion'];

        $ci_elab     = $usuario['nodip'];
        $des_usuario = $usuario['usuario'];
        $gestion     = $usuario['gestion'];

        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $id = FixedAsset::StoreDataAuditorDetails($id, $actualizacion, $seguridad, $proteccion, $capacitacion);
                $id = $id[0]->{'ff_registrar_detalle_auditor'};
                break;
            case 'editar':
            default:
                break;
        }
        return json_encode($id);
    }

    //  *  AF21. Guarda la informacion necesaria para crear activos fijos dentro de un documento de revaluo          
    public function storeDataRevaluedDetails(Request $request)
    {

        $documento = $request->get('assignment');
        $item      = $request->get('fixedAsset');
        $usuario   = $request->get('user');
        $marcador  = $request->get('marker');

        \Log::info($documento['id']);
        $id_documento = $documento['id'];
        $cod_tipo     = $documento['cod_tipo'];
        $cod_prg      = strtoupper($documento['cod_prg']);
        $ref_prg      = strtoupper($documento['ref_prg']);

        $ci_elab     = $usuario['nodip'];
        $des_usuario = $usuario['usuario'];
        $gestion     = $usuario['gestion'];

        $indice            = $item['id'];
        $codigo            = strtoupper($item['codigo']);
        $marca             = strtoupper($item['des_marca']);
        $modelo            = strtoupper($item['des_modelo']);
        $descripcion       = strtoupper($item['descripcion']);
        $medida            = strtoupper($item['medida']);
        $cantidad          = $item['cantidad'];
        $importe           = $item['importe'];
        $fecha_adquisicion = $item['fecha_adquisicion'];
        $id_contable       = $item['id_contable'];
        $des_contable      = $item['des_contable'];
        $id_presupuesto    = $item['id_presupuesto'];
        $des_presupuesto   = $item['des_presupuesto'];
        $estado            = $item['estado'];
        $adicional         = json_encode($item['adicional']); //, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        //$id_eliminar = FixedAsset::RemoveDataAssignmentDetails($id_documento);
        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $id = FixedAsset::StoreDataAssignmentDetails($id_documento, $indice, $codigo, $marca, $modelo, $descripcion, $medida, $cantidad, $importe, $fecha_adquisicion, $id_contable, $des_contable, $id_presupuesto, $des_presupuesto, $estado, $adicional, $des_usuario);
                $id = $id[0]->{'ff_registrar_asignacion_detallada'};
                return json_encode($id);
                break;
            case 'editar':
                $id_activo = $item['id'];
                $id        = FixedAsset::UpdateDataAssignmentDetails($id_activo, $id_documento, $indice, $codigo, $marca, $modelo, $descripcion, $medida, $cantidad, $importe, $fecha_adquisicion, $id_contable, $des_contable, $id_presupuesto, $des_presupuesto, $estado, $adicional, $des_usuario);
                $id        = $id[0]->{'ff_actualizar_asignacion_detallada'};
                break;
            default:
                break;
        }
        return json_encode($id);
    }

    //  *  AF25. Quita un activo fijo de la revaluacion     
    //Route::post('setRemoveDataAssignmentDetails/', 'FixedAssetController@setRemoveDataAssignmentDetails');
    public function setRemoveDataAssignmentDetails(Request $request)
    {
        $id = $request->get('id');
        $id = FixedAsset::SetRemoveDataAssignmentDetails($id);
        return json_encode(['id' => $id]);
    }

    //  *  AF26. Obtiene un activo fijo de la lista de actualizaciones y depreciaciones por su id
    //Route::post('getDataFixedAssetDetailsById/', 'FixedAssetController@getDataFixedAssetDetailsById');
    public function getDataFixedAssetDetailsById(Request $request)
    {
        $id                     = $request->get('id');
        $gestion                = $request->get('year');
        $gestion                = '2024';
        $dataFixedAssetRevalued = FixedAsset::GetDataFixedAssetDetailsById($id, $gestion);
        return json_encode(['dataFixedAssetRevalued' => $dataFixedAssetRevalued]);
    }

    
    public function getDataRevaluedDetails(Request $request)
    {
        $id             = $request->get('id');
        $gestion        = $request->get('year');
        $dataFixedAsset = FixedAsset::GetFixedAssetsAssignmentDetails2($id);
        $dataRevalued   = FixedAsset::GetFixedAssetsRevalued($id);
        $imageDocuments   = FixedAsset::GetImagenByRevaluedAssignment($id);
        return json_encode(['dataFixedAsset' => $dataFixedAsset, 'dataRevalued' => $dataRevalued, 'imageDocuments' => $imageDocuments]);
    }

    //  *  AF24. Guarda la informacion necesaria para los datos de revaluo del activos fijos dentro de un documento de revaluo       
    public function storeDataThecnicalRevaluedDetails(Request $request)
    {
        $item         = $request->get('dataFixedAsset');
        $tecnico      = $request->get('dataTechnicals');
        $resultados   = $request->get('dataResult');
        $cotizaciones = $request->get('dataQuotes');
        $usuario      = $request->get('user');
        $marcador     = $request->get('marker');

        $ci_elab     = $usuario['nodip'];
        $des_usuario = $usuario['usuario'];
        $gestion     = $usuario['gestion'];

        $indice            = $item['id'];
        $descripcion       = strtoupper($item['descripcion']);
        $medida            = strtoupper($item['medida']);
        $cantidad          = $item['cantidad'];
        $importe           = $item['importe'];
        $fecha_adquisicion = $item['fecha_adquisicion'];
        $id_contable       = $item['id_contable'];
        $des_contable      = $item['des_contable'];
        $id_presupuesto    = $item['id_presupuesto'];
        $des_presupuesto   = $item['des_presupuesto'];
        $estado            = $item['estado'];
        $accesorios        = json_encode($item['accesorios']); //, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $perdida       = $tecnico['perdida'];
        $funcionalidad = $tecnico['funcionalidad'];
        $obsolescencia = $tecnico['obsolescencia'];
        $conservacion  = $tecnico['conservacion'];
        $uso           = $tecnico['uso'];
        $mantenimiento = $tecnico['mantenimiento'];
        $resultado     = $tecnico['resultado'];

        $estado         = $resultados['estado'];
        $vida           = $resultados['vida'];
        $valor_residual = $resultados['valor_residual'];
        $valor_revaluo  = $resultados['valor_revaluo'];
        $valor_saldo    = $resultados['valor_saldo'];

        $cotizaciones = json_encode($cotizaciones); //, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $id = FixedAsset::StoreDataRevaluedDetails($indice, $id_contable, $perdida, $funcionalidad, $obsolescencia, $conservacion, $uso, $mantenimiento, $cotizaciones, $des_usuario);
                $id = $id[0]->{'ff_registrar_revaluo_detallado'};
                return json_encode($id);
                break;
            case 'editar':
                $id_revalued = $request->get('id_revalued');
                $id          = FixedAsset::UpdateDataRevaluedDetails($id_revalued, $id_contable, $perdida, $funcionalidad, $obsolescencia, $conservacion, $uso, $mantenimiento, $cotizaciones, $des_usuario);
                $id          = $id[0]->{'ff_actualizar_revaluo_detallado'};
                break;
            default:
                break;
        }
        return json_encode($id);
    }

}
