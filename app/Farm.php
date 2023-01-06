<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Farm extends Model
{
    //  * G1. Obtiene la lista de los dias de venta de los productos de la granja 
    //  * Route::post('getFarmSaleDays', 'FarmController@getFarmSaleDays');
    public static function GetFarmSaleDays($gestion)
    {
        $query = "select * from vgra.diario where gestion = '" . $gestion . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G2. Agregar un nuevo dia de venta de los productos de la granja
    //  * Route::post('storeFarmSaleDays', 'FarmController@storeFarmSaleDays');
    public static function AddFarmSaleDays($gestion, $fecha, $tip_tra, $usr_cre)
    {
        $query = "select * from vgra.ff_nuevo_dia('" . $gestion . "'::smallint,'" . $fecha . "'::date," . $tip_tra . ",'" . $usr_cre . "')";
        $data = collect(DB::select(DB::raw($query)));
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
                . $gestion .",'" . $usr_cre ."')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


    //  * G4. Obtener el numero de comprobante para la venta de productos
    //  * Route::post('getVoucherNumber', 'FarmController@getVoucherNumber');
    public static function GetVoucherNumber($id_dia, $usr_cre)
    {
        $query = "select * from vgra.ff_nuevo_dia('" . $id_dia . "','" . $fusr_cre . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;        
    }

    //  * G5. Obtiene un dia de venta de los productos de la granja 
    public static function GetFarmSaleDayById($id)
    {
        $query = "select * from vgra.diario where id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G6. Obtiene un producto a traves de su codigo 
    public static function GetProductForSale($id)
    {
        $query = "select * from vgra.producto where cod_prd = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * G7. Obtiene el numero de comprobante para la venta actual 
    public static function GetCurrentVoucherNumber($id_dia, $usr_cre){
        $query = "select * from vgra.ff_numero_com('" . $id_dia . "','" . $usr_cre . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;        
    }
}
