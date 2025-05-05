<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Farm extends Model
{
    //  * G1. Obtiene la lista de los dias de venta de los productos de la granja
    //  * Route::post('getFarmSaleDays', 'FarmController@getFarmSaleDays');
    public static function GetFarmSaleDays($tipo_transaccion, $gestion)
    {

        switch ($tipo_transaccion) {
            case 1:  //venta al contado
            case 14: //venta al credito
                $query = "select * from vgra.diario where gestion = '" . $gestion . "' and tip_tra in (1, 14) order by fec_tra, idx";
                break;
            case 2: //ingreso
                $query = "select * from vgra.diario where gestion = '" . $gestion . "' and tip_tra in (2) order by fec_tra, idx";
                break;
            case 8: //baja
                $query = "select * from vgra.diario where gestion = '" . $gestion . "' and tip_tra in (8) order by fec_tra, idx";
                break;
            case 0: //regularizacion
                $query = "select * from vgra.diario where gestion = '" . $gestion . "' and tip_tra in (0) order by fec_tra, idx";
                break;
            default:
                $query = "select * from vgra.diario where gestion = '" . $gestion . "' order by fec_tra, idx";
        }
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G2. Agregar un nuevo dia de venta de los productos de la granja
    //  * Route::post('storeFarmSaleDays', 'FarmController@storeFarmSaleDays');
    public static function AddFarmSaleDays($gestion, $fecha, $tip_tra, $usr_cre)
    {
        $query = "select * from vgra.ff_nuevo_dia('" . $gestion . "'::smallint,'" . $fecha . "'::date," . $tip_tra . ",'" . $usr_cre . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function UpdateFarmSaleDays($dia, $gestion, $fecha, $tip_tra, $usr_cre)
    {
    }

    //  * G3. Agregar detalle de ventas del cliente de los productos de la granja
    //  * Route::post('storeCustomerSaleDetail', 'FarmController@storeCustomerSaleDetail');
    public static function StoreCustomerSaleDetail($id_dia, $fec_tra, $gestion, $usr_cre, $ci_per, $des_per, $nro_com, $cod_prd, $can_prd, $pre_uni, $tip_tra, $imp_total)
    {
        //select * from vgra.ff_nueva_venta(...)
        $query = "insert into vgra.dia_des(id_dia, cod_pro, can_pro, uni_pro, imp_pro, nro_com, tip_tra, ci_per, des_per, fecha, gestion, usr_cre) values ('"
            . $id_dia . "','" . $cod_prd . "'," . $can_prd . ","
            . $pre_uni . "," . $imp_total . ",'" . $nro_com . "'," . $tip_tra . ",'"
            . $ci_per . "','" . $des_per . "','" . $fec_tra . "',"
            . $gestion . ",'" . $usr_cre . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G4. Obtener el numero de comprobante para la venta de productos
    //  * Route::post('getVoucherNumber', 'FarmController@getVoucherNumber');
    public static function GetVoucherNumber($id_dia, $usr_cre)
    {
        $query = "select * from vgra.ff_nuevo_dia('" . $id_dia . "','" . $usr_cre . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G5. Obtiene un dia de venta de los productos de la granja
    public static function GetFarmSaleDayById($id)
    {
        $query = "select * from vgra.diario where id = '" . $id . "'";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G6. Obtiene un producto a traves de su codigo
    public static function GetProductForSale($id)
    {
        $query = "select * from vgra.producto where cod_prd = '" . $id . "'";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G7. Obtiene el numero de comprobante para la venta actual
    public static function GetCurrentVoucherNumber($id_dia)
    {
        $query = "select * from vgra.ff_numero_com('" . $id_dia . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G9. obtener todas las ventas correspondientes a un dia en especifico
    public static function GetFarmSaleDetailById($id)
    {
        $query = "select *, a.id as id_tran from vgra.dia_des a inner join vgra.producto b on a.cod_pro = b.cod_prd where id_dia = '" . $id . "' order by nro_com";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * G11. Cerrar el reporte de ventas del dia.
    public static function setCloseSaleDetailDay($id_dia, $gestion)
    {
        \Log::info($id_dia);
        \Log::info($gestion);
        $query = "update vgra.diario set estado = 'V', " .
            "importe = (select sum(d.imp_pro) from vgra.dia_des d where d.id_dia = " . $id_dia . " and d.tip_tra<> 9), " .
            "com_fin = (select max(d.nro_com) from vgra.dia_des d where d.id_dia = " . $id_dia . ") " .
            "where id = " . $id_dia . " and gestion = '" . $gestion . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * G12. Anular la transaccion de una venta erronea.
    public static function UpdateCancelTransaction($id, $nro_com, $tip_tra, $gestion)
    {
        $query = "update vgra.dia_des set tip_tra = '9' " .
            "where id = " . $id . " and nro_com = '" . $nro_com . "' and tip_tra = " . $tip_tra . " and gestion = '" . $gestion . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G14. Obtiene la cantidad de productos registrados
    public static function GetCurrentProductsById($id)
    {
        $query = "select a.can_pro as can, b.cod_prd, b.des_prd as des_prd, b.id as id, a.uni_pro as pre_uni, b.tip_prd as tip_prd, b.uni_prd as uni_prd from vgra.dia_des a inner join vgra.producto b on a.cod_pro = b.cod_prd where id_dia = '" . $id . "' and a.tip_tra <> 9";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function RemoveCurrentProductById($id)
    {
        $query = "update vgra.dia_des set tip_tra = '9' where id_dia = '" . $id . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function RemoveCurrentClientById($id)
    {
        $query = "update vgra.dia_des set tip_tra = '9' where id_dia = '" . $id . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G14. Obtiene la cantidad de productos registrados
    public static function GetClientsForRegularize($descripcion)
    {
        $query = "select * from vgra.ff_datos_deudor('" . $descripcion . "') limit 1";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G19. Obtiene los clientes registrados
    public static function GetCurrentRegularizeClientById($id)
    {
        $query = "select * from vgra.ff_amortizaciones_dia('" . $id . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G19. Obtiene los clientes registrados
    public static function GetTransactionsSaleByDays($inicial, $final)
    {
        $query = "select * from vgra.diario where fec_tra >= '" . $inicial . "' and fec_tra <='" . $final . "' and tip_tra in (1, 14)";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G23. Kardex fisico valorado
    public static function GetKardexById($id, $year, $inicial, $final)
    {
        $query = "select * from vgra.ff_kardex_fisico('" . $id . "','" . $year . "','" . $inicial . "','" . $final . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G25. Obtiene la lista de los resumenes de los dias de venta de los productos de la granja
    //  * Route::post('getSaleSummaryDays', 'FarmController@getSaleSummaryDays');
    public static function GetFarmSummaryDays($tipo_transaccion, $gestion)
    {

        $query = "select * from vgra.diario_resumen where gestion = '" . $gestion . "' order by idx";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
