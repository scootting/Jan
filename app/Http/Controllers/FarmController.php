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

        if($tip_tra = 2)
            $nro_com = '000000';
        else
            $nro_com = $request->get('voucher');
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
        \Log::info("DATOS PARA LA IMPRESION DE BOUCHER");
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
    public function customerSaleDetailDayReport(Request $request){
        $id = $request->get('id');
        $gestion = $request->get('gestion');
        \Log::info($id);
        \Log::info($gestion);
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
    public function updateCancelTransaction(Request $request){
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
}
