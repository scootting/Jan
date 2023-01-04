<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Farm;
use Illuminate\Pagination\LengthAwarePaginator;

class FarmController extends Controller
{

    //  * G1. Obtiene la lista de los dias de venta de los productos de la granja 
    //  * Route::post('getFarmSaleDays', 'FarmController@getFarmSaleDays');
    public function getFarmSaleDays(Request $request)
    {
        $year = strtoupper($request->get('gestion')); // '' cadena vacia
        $data = Farm::GetFarmSaleDays($year);
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
        $dataDays = $request->get('day');
        $dataClient = $request->get('client');
        $dataDetails = $request->get('details');

        $id_dia = $dataDays['id_dia'];
        $fec_tra = $dataDays['fec_tra'];
        $usr_cre = $dataDays['usr_cre'];
        $gestion = $dataDays['gestion'];

        $ci_per = strtoupper($dataClient['ci_per']);
        $des_per = strtoupper($dataClient['des_per']);

        foreach ($dataDetails as $item) {
            # code...
            $cod_val = $item['cod_val'];
            $can_val = $item['can_val'];
            $pre_uni = $item['pre_uni_val'];
            $imp_val = $item['imp_val'];
            $tip_tra = $item['tip_tra'];
            $data = Farm::storeCustomerSaleDetail($id_dia, $id_tran, -1, $cod_val, $ci_per, $des_per, -1, $gestion, $des_tra, $pre_uni);
        }
        return json_encode($id_tran);
    }


    //  * G4. Obtener el numero de comprobante para la venta de productos
    //  * Route::post('getVoucherNumber', 'FarmController@getVoucherNumber');
    public function getVoucherNumber(Request $request){
        $id_dia = $request->get('id');
        $usr_cre = $request->get('usr');
        $data = Farm::GetVoucherNumber($id_dia, $usr_cre);
        return json_encode($data);
    }
}
