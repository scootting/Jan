<?php

namespace App\Http\Controllers;

use App\Farm;
use Illuminate\Http\Request;
use App\Libraries\JSRClient;
use Illuminate\Pagination\LengthAwarePaginator;

class FarmController extends Controller
{

    //  * G1. Obtiene la lista de los dias de venta de los productos de la granja
    //  * Route::post('getFarmSaleDays', 'FarmController@getFarmSaleDays');
    public function getFarmSaleDays(Request $request)
    {
        $type_transaction = $request->get('transaccion');
        $year = strtoupper($request->get('gestion'));
        $data = Farm::GetFarmSaleDays($type_transaction, $year);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getFarmSaleDays')]
        );
        return json_encode($paginate);
    }

    //  * G2. Agregar un nuevo dia de venta de los productos de la granja
    //  * Route::post('storeFarmSaleDays', 'FarmController@storeFarmSaleDays');
    public function storeFarmSaleDays(Request $request)
    {
        $dia = $request->get('dia');
        $usuario = $request->get('usuario');
        $fecha = $dia['fecha'];
        $tip_tra = $dia['tip_tra'];
        $gestion = $usuario['gestion'];
        $usr_cre = $usuario['usuario'];
        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                $data = Farm::AddFarmSaleDays($gestion, $fecha, $tip_tra, $usr_cre);
                break;
            case 'editar':
                $data = Farm::UpdateFarmSaleDays($dia, $gestion, $fecha, $tip_tra, $usr_cre);
                break;
            default:
                break;
        }
        return json_encode($data);
    }

    //  * G3. Agregar detalle de ventas del cliente de los productos de la granja
    //  * Route::post('storeCustomerSaleDetail', 'FarmController@storeCustomerSaleDetail');
    public function storeCustomerSaleDetail(Request $request)
    {
        $id_tran = 0;
        $dataDays = $request->get('general');
        $dataClient = $request->get('cliente');
        $dataProducts = $request->get('productos');

        $id_dia = $dataDays['id']; //manda la key y no el el id_dia
        $fec_tra = $dataDays['fec_tra'];
        $usr_cre = $dataDays['usr_cre'];
        $gestion = $dataDays['gestion'];
        $tip_tra = $dataDays['tip_tra'];

        if ($tip_tra == 2 or $tip_tra == 0 or $tip_tra == 8) {
            $nro_com = '000000';
            Farm::RemoveCurrentProductById($id_dia);
        } else {
            $nro_com = $request->get('voucher');
        }
        $ci_per = strtoupper($dataClient['id']);
        $des_per = strtoupper($dataClient['details']);

        foreach ($dataProducts as $item) {
            # code...
            $cod_prd = $item['cod_prd'];
            $can_prd = $item['can'];
            $pre_uni = $item['pre_uni'];
            $imp_total = $can_prd * $pre_uni;
            $data = Farm::StoreCustomerSaleDetail($id_dia, $fec_tra, $gestion, $usr_cre, $ci_per, $des_per, $nro_com, $cod_prd, $can_prd, $pre_uni, $tip_tra, $imp_total);
        }
        return json_encode($id_tran);
    }

    //  * G4. Obtener el numero de comprobante para la venta de productos
    //  * Route::post('getVoucherNumber', 'FarmController@getVoucherNumber');
    public function getVoucherNumber(Request $request)
    {
        $id_dia = $request->get('id');
        $usr_cre = $request->get('usr');
        $data = Farm::GetVoucherNumber($id_dia, $usr_cre);
        return json_encode($data);
    }

    //  * G5. Obtiene un dia de venta de los productos de la granja
    public function getFarmSaleDayById(Request $request)
    {
        $id_dia = $request->get('id');
        $data = Farm::GetFarmSaleDayById($id_dia);
        return json_encode($data);
    }

    //  * G6. Obtiene un producto a traves de su codigo
    public function getProductForSale(Request $request)
    {
        $cod_prd = $request->get('codigo');
        $data = Farm::GetProductForSale($cod_prd);
        return json_encode($data);
    }

    //  * G7. Obtiene el numero de comprobante para la venta actual
    public function getCurrentVoucherNumber(Request $request)
    {
        $id_dia = $request->get('id_dia');
        $data = Farm::GetCurrentVoucherNumber($id_dia);
        \Log::info($data);
        $idx = $data[0]->{'ff_numero_com'};
        \Log::info($idx);
        return json_encode($idx);
    }

    //  * G8. Imprimir el reporte de la venta actual.
    public function customerSaleDetailReport(Request $request)
    {
        $nro_com = $request->get('voucher');
        $tip_tra = $request->get('tipo');
        $gestion = $request->get('gestion');//$dataDays['gestion'];
        $nreport = 'DetailCreditSaleLetter';
        $controls = array(
            'p_nro_com' => trim($nro_com),
            'p_tip_tra' => $tip_tra,
            'p_gestion' => $gestion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * G9. obtener todas las ventas correspondientes a un dia en especifico
    public function getFarmSaleDetailById(Request $request)
    {
        $id_dia = $request->get('id');
        $data = Farm::GetFarmSaleDetailById($id_dia);
        return json_encode($data);
    }

    //  * G10. Imprimir el reporte de ventas del dia.
    public function customerSaleDetailDayReport(Request $request)
    {
        $id = $request->get('id');
        $gestion = $request->get('gestion');
        $nreport = 'FarmSaleDetailsDay_Letter';
        $controls = array(
            'p_id' => $id,
            'p_gestion' => $gestion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }
    //  * G11. Cerrar el reporte de ventas del dia.
    public function setCloseSaleDetailDay(Request $request)
    {
        $id_dia = $request->get('id');
        $gestion = $request->get('gestion');
        $data = Farm::SetCloseSaleDetailDay($id_dia, $gestion);
        return json_encode($data);
    }

    //  * G12. Anular la transaccion de una venta erronea.
    public function updateCancelTransaction(Request $request)
    {
        $id = $request->get('id');
        $nro_com = $request->get('voucher');
        $tip_tra = $request->get('tipo');
        $gestion = $request->get('gestion');//$dataDays['gestion'];
        $data = Farm::UpdateCancelTransaction($id, $nro_com, $tip_tra, $gestion);
        return json_encode($data);
    }

    //  * G13. Imprimir el reporte del ingreso actual.
    public function customerIncomeDetailReport(Request $request)
    {
        $nro_com = $request->get('voucher');
        $tip_tra = $request->get('tipo');
        $gestion = $request->get('gestion');//$dataDays['gestion'];
        \Log::info("DATOS PARA LA IMPRESION DE BOUCHER");
        $nreport = 'DetailIncomeLetter';
        $controls = array(
            'p_id' => $id,
            'p_tip_tra' => $tip_tra,
            'p_gestion' => $gestion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * G14. Obtiene la cantidad de productos registrados
    public function GetCurrentProductsById(Request $request)
    {
        $id_dia = $request->get('id_dia');
        $data = Farm::GetCurrentProductsById($id_dia);
        \Log::info($data);
        return json_encode($data);
    }

    //  * G14. Obtiene la cantidad de productos registrados
    public function getClientsForRegularize(Request $request)
    {
        $descripcion = $request->get('description');
        $data = Farm::GetClientsForRegularize($descripcion);
        \Log::info($data);
        return json_encode($data);
    }

    //  * G15. Agregar detalle de las regularizaciones del cliente de los productos de la granja
    public function storeCustomerRegularizeDetail(Request $request)
    {
        $id_tran = 0;
        $dataDays = $request->get('general');
        //$dataClient = $request->get('cliente');
        //$dataProducts = $request->get('productos');
        $dataClients = $request->get('clientes');


        $id_dia = $dataDays['id']; //manda la key y no el el id_dia
        $fec_tra = $dataDays['fec_tra'];
        $usr_cre = $dataDays['usr_cre'];
        $gestion = $dataDays['gestion'];
        $tip_tra = $dataDays['tip_tra'];

        $nro_com = '000000';
        Farm::RemoveCurrentClientById($id_dia);

        foreach ($dataClients as $item) {
            # code...
            $ci_per = strtoupper($item['ci_per']);
            $des_per = strtoupper($item['des_per']);
            $cod_prd = '0000';
            $can_prd = 1;
            $pre_uni = $item['total_pago'];
            $imp_total = $can_prd * $pre_uni;
            $data = Farm::StoreCustomerSaleDetail($id_dia, $fec_tra, $gestion, $usr_cre, $ci_per, $des_per, $nro_com, $cod_prd, $can_prd, $pre_uni, $tip_tra, $imp_total);
        }
        return json_encode($id_tran);
    }

    //  * G17. Imprimir el reporte de ingresos del dia.
    public function incomeDetailDayReport(Request $request)
    {
        $id = $request->get('id');
        $tipo_transaccion = $request->get('tipo_transaccion');
        $gestion = $request->get('gestion');
        \Log::info($id);
        \Log::info($gestion);
        $nreport = 'FarmIncomeDetailsDay_Letter';
        $controls = array(
            'p_id' => $id,
            'p_tip_tra' => $tipo_transaccion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * G18. Imprimir la baja de ingresos del dia.
    public function customerDropDetailReport(Request $request)
    {
        $id = $request->get('id');
        $gestion = $request->get('gestion');
        \Log::info($id);
        \Log::info($gestion);
        $nreport = 'FarmDropDetailsDay_Letter';
        $controls = array(
            'p_id' => $id,
            'p_gestion' => $gestion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * G19. Obtiene los clientes registrados
    public function getCurrentRegularizeClientById(Request $request)
    {
        $id_dia = $request->get('id_dia');
        $data = Farm::GetCurrentRegularizeClientById($id_dia);
        \Log::info($data);
        return json_encode($data);
    }

    //  * G20. Lista de dias en el rango
    public function getTransactionsSaleByDays(Request $request)
    {
        $dataDays = $request->get('range');
        $fecha_inicial = $dataDays['initial'];
        $fecha_final = $dataDays['final'];
        $data = Farm::GetTransactionsSaleByDays($fecha_inicial, $fecha_final);
        \Log::info($data);
        return json_encode($data);
    }

    //  * G21. Imprimir el reporte de movimientos de las ventas de los dias.
    public function customerResumeSaleDetailDaysReport(Request $request)
    {
        $fecha_inicial = $request->get('initial');
        $fecha_final = $request->get('final');
        $nreport = 'FarmResumeSaleDetailsDay_Letter';
        \Log::info($fecha_inicial);
        \Log::info($fecha_final);
        $controls = array(
            'p_id' => 1,
            'p_final' => $fecha_final,
            'p_inicial' => $fecha_inicial,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * G22. Imprimir el kardex de un producto.
    public function farmKardexByProductReport(Request $request)
    {
        $id = $request->get('codigo');
        $gestion = $request->get('gestion');
        $inicial = $request->get('inicial');
        $final = $request->get('final');
        \Log::info($id);
        \Log::info($gestion);
        $nreport = 'FarmKardexByProduct';
        $controls = array(
            'p_codigo' => $id,
            'p_gestion' => $gestion,
            'p_inicial' => $inicial,
            'p_final' => $final,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * G23. Kardex fisico valorado
    public function getKardexById(Request $request)
    {
        $id = $request->get('id');
        $gestion = $request->get('year');
        $dataDays = $request->get('range');
        $fecha_inicial = $dataDays['initial'];
        $fecha_final = $dataDays['final'];
        $data = Farm::GetKardexById($id, $gestion, $fecha_inicial, $fecha_final);
        return json_encode($data);
    }

    //  * G24. Imprimir el reporte de regularizaciones del dia.
    public function regularizeDetailDayReport(Request $request)
    {
        $id = $request->get('id');
        $gestion = $request->get('gestion');
        $nreport = 'FarmRegularizeDetailsDay_Letter';
        $controls = array(
            'p_id' => $id,
            'p_gestion' => $gestion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    //  * G25. Obtiene la lista de los resumenes de los dias de venta de los productos de la granja
    //  * Route::post('getSaleSummaryDays', 'FarmController@getSaleSummaryDays');
    public function getFarmSummaryDays(Request $request)
    {
        $type_transaction = $request->get('transaccion');
        $year = strtoupper($request->get('gestion'));
        $data = Farm::GetFarmSummaryDays($type_transaction, $year);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getFarmSummaryDays')]
        );
        return json_encode($paginate);
    }

}
