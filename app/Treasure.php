<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Treasure extends Model
{

    //  * T1. Obtener una lista de las transacciones realizadas de un usuario en Cajas.
    //  * {id: numero de carnet de identidad}
    public static function getTransactionsByPerson($id)
    {
        //SELECT * FROM bval.ff_transacciones_persona('6600648')
        $query = "SELECT * FROM bval.ff_transacciones_persona('" . $id . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * T2. Obtener una lista de las ventas en linea solicitadas durante la gestion.
    //  * {year: año de ingreso}
    public static function GetSaleInLine($year)
    {
        //SELECT * FROM bval.ff_transacciones_persona('6600648')
        $query = "select * from linea.solicitudes s where s.gestion = '" . $year . "' and s.tipo ='SALE' order by fec_cre desc";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * T3. Obtener una lista de las ventas en linea solicitadas durante la gestion.
    //  * {year: año de ingreso}
    public static function GetSaleInLineDetail($year)
    {
        //SELECT * FROM bval.ff_transacciones_persona('6600648')
        $query = "select * from linea.solicitudes s where s.gestion = '" . $year . "' and s.tipo ='SALE' order by fec_cre desc";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //obtiene datos de la solicitud
    public static function GetDataRequestById($id)
    {
        $query = "select * from linea.solicitudes s where s.id = " . $id;
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtiene los valores registrados en la solicitud
    public static function GetDetailRequestById($id)
    {
        $query = "select * from linea.valores_solicitud v where v.id_sol = " . $id;
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtiene los boucher registrados en la solicitud
    public static function getBoucherRequestById($id)
    {
        $query = "select id, id_sol, boucher, fecha, imp_bou, ruta from linea.deposito_solicitud d where d.id_sol = " . $id;
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //obtiene los documentos que corresponden al extracto bancario
    public static function getExtractBankById($id)
    {
        $query = "select * from linea.extracto e where e.id_doc in (select d.boucher from linea.deposito_solicitud d " .
            "where d.id_sol = " . $id . ")";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //obtiene todos los boucher de banco solicitados del extracto bancario
    public static function getAllExtractBank()
    {
        $query = "select * from linea.extracto e where e.estado = 'Solicitado' ";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * T40 trae el documento digitalizado
    //  * {id: id del boucher digitalizado }
    public static function GetDigitalBoucher($id, $year)
    {
        $query = "SELECT img as pdf_data FROM linea.deposito_solicitud d WHERE d.id = ?";
        \Log::info($query);
        $data = DB::select($query, [$id]);
        return $data;
    }

    public static function addStoreRequestSaleInLine($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, $nro_com, $ci_per, $des_per, $tip_tra, $gestion)
    {
        //insert into val.tra_dia( ... ) values ( ... ) RETURNING id_tran
        $query = "INSERT INTO val.tra_dia(id_dia, cod_val, can_val, pre_uni, fec_tra, usr_cre," .
            "nro_com, ci_per, des_per, tip_tra, gestion) VALUES " .
            "('" . $id_dia . "','" . $cod_val . "','" . $can_val . "','" . $pre_uni . "','" . $fec_tra . "','" . $usr_cre . "','" .
            $nro_com . "','" . $ci_per . "','" . $des_per . "','10','" . $gestion . "') RETURNING id_tran";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function storeChangeStateExtract($id_extract, $state, $id_dia)
    {
        $query = "UPDATE linea.extracto set estado = '" . $state . "', id_dia = '" . $id_dia . "' where id = '" . $id_extract . "'";
        $data  = collect(DB::select(DB::raw($query)));

        return $data;
    }
    public static function StoreChangeStateRequest($id_request, $state)
    {
        $query = "UPDATE linea.solicitudes set estado = '" . $state . "' where id = '" . $id_request . "'";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetTransactionsByDay($id_dia)
    {
        $query = "select * from val.tra_dia d where d.id_dia = '" . $id_dia . "' and d.tip_tra <> 9";
        \Log::info($query);
        \Log::info($query);
        \Log::info($query);
        \Log::info($query);
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad.
    //  * {id: numero de carnet de identidad}
    //  * {year: gestion}
    public static function getDataOfStudentById($id, $year)
    {
        //select * from cluster.f_nuevos_datacenter('10547123', '2019', '2')
        $query = "select * from cluster.f_nuevos_datacenter_2('" . $id . "','" . $year . "','1') where r_aceptado = 'ACEPTADO' and gestion ='" . $year . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * Encontrar los valores de un tramite a traves de su descripcion.
    //  * {description: descripcion del tramite}
    //  * {year: gestion}
    public static function getValuesProcedure($description, $year)
    {
        \Log::info('holas en este estado:' . $year);
        //select * from trap.ff_valores_tramite_nuevos('EXCELENCIA', '2020')
        $query = "select * from trap.ff_valores_tramite_nuevos('" . $description . "','" . $year . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * T30. Obtienes los dias de venta en linea para manhattan, nottingham, vancouber
    public static function GetOnlineSalesDays($description, $year)
    {
        //select * from val.diario vd where vd.usr_cre = 'rcallizaya' and vd.gestion = '2020'
        if ($description == '') {
            $query = "select * from val.diario vd where vd.usr_cre in ('manhattan', 'nottingham', 'vancouver', 'petrogrado') and vd.gestion = '" . $year . "' order by vd.fec_tra, vd.id_dia desc";
        } else {
            //$query = "select * from val.diario vd where vd.usr_cre = '" . $user . "' and vd.gestion = '" . $year . "' order by vd.id_dia desc";
        }

        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * T40. Obtienes los pagos
    public static function GetGatewayPayments($description, $fecha_inicial, $fecha_final)
    {
        //select * from val.diario vd where vd.usr_cre = 'rcallizaya' and vd.gestion = '2020'
        $year  = 'PROCESADO';
        $query = "SELECT * FROM ppe.ff_filtrar_pagos('" . $description . "', '" . $fecha_inicial . "', '" . $fecha_final . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * Encontrar los valores de un tramite a traves de su descripcion.
    //  * {description: descripcion de la busqueda}
    //  * {user: usuario}
    //  * {year: gestion}
    public static function getSaleOfDaysByDescription($description, $user, $year)
    {
        //select * from val.diario vd where vd.usr_cre = 'rcallizaya' and vd.gestion = '2020'
        if ($description == '') {
            $query = "select * from val.diario vd where vd.usr_cre = '" . $user . "' and vd.gestion = '" . $year . "' order by vd.id_dia desc";
        } else {
            $query = "select * from val.diario vd where vd.usr_cre = '" . $user . "' and vd.gestion = '" . $year . "' order by vd.id_dia desc";
        }

        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * Encontrar los valores de un tramite a traves de su descripcion.
    //  * {id: id del dia que se va a utilizar}
    //  * {user: usuario}
    //  * {year: gestion}
    public static function getSaleOfDayById($id, $user, $year)
    {
        $user = 'nottingham';
        //select * from val.diario vd where vd.id_dia = '1234' and vd.usr_cre = 'rcallizaya' and vd.gestion = '2020'
        $query = "select * from val.diario vd where vd.id_dia = '" . $id . "' and vd.usr_cre = '" . $user . "' and vd.gestion = '" . $year . "'";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * T22. Agrega un nuevo dia para la venta de valores para estudiantes nuevos
    public static function storeDayForSale($user, $year)
    {
        //$query = "insert into val.diario(fec_tra, glosa, estado, tip_mon, importe, id_lugar, gestion, tip_tra, nro_com_min, usr_cre)" .
        //    "values (now(), 'Venta: De La Universidad Autónoma \"Tomás Frías\" En BOLIVIANOS', 'C', 'B', 0, 'U', '" . $year . "', 0, '-1', '" . $user . "')";
        $query = "select * from val.ff_registrar_dia_venta('" . $user . "', '" . $year . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function addTransactionsByStudents($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, $nro_com, $ci_per, $des_per, $tip_tra, $gestion, $desde, $hasta)
    {
        //cambiar a tra_dia_2
        //insert into val.tra_dia( ... ) values ( ... ) RETURNING id_tran
        $query = "INSERT INTO val.tra_dia(id_dia, cod_val, can_val, pre_uni, fec_tra, usr_cre," .
            "nro_com, ci_per, des_per, tip_tra, gestion, desde, hasta, imp_val) VALUES " .
            "('" . $id_dia . "','" . $cod_val . "','" . $can_val . "','" . $pre_uni . "','" . $fec_tra . "','" . $usr_cre . "','" .
            $nro_com . "','" . $ci_per . "','" . $des_per . "','" . $tip_tra . "','" . $gestion . "'," . $desde . "," . $hasta . "," . $pre_uni . ") RETURNING id_tran";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function getIdTransactionsByYear($gestion)
    {
        $query = "select * from trap.ff_id_tramite('" . $gestion . "')";
        $data  = collect(DB::select(DB::raw($query)));
        \Log::info("este es el numero de tramite: " . $data);
        return $data;
    }

    public static function addProcedureByStudents($id_dia, $id_tran, $nro_sol, $cod_val, $ci_per, $des_per, $idx, $gestion, $des_tra, $imp_val)
    {
        //insert into trap.doc( ... ) values ( ... )
        $query = "INSERT INTO trap.doc(id_dia, id_tran, nro_sol, cod_val, " .
            "ci_per, des_per, idx, gestion, des_tra, imp_val) VALUES " .
            "('" . $id_dia . "','" . $id_tran . "','" . $nro_sol . "','" . $cod_val . "','" .
            $ci_per . "','" . $des_per . "','" . $idx . "','" . $gestion . "','" . $des_tra . "','" . $imp_val . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * Encontrar todas las transacciones de una gestion.
    //  * {id: numero de carnet de identidad}
    public static function GetAllTransactionsByYear($description, $year)
    {
        //select * from val.ff_buscar_transacciones('66006048', '2021');
        $query = "select * from val.ff_buscar_transacciones('" . $description . "','" . $year . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function CancelTransactionById($id, $day, $year, $user, $type)
    {
        //select * from val.ff_anular_transaccion('1234567890','1020', '2021', 'rcallizaya')
        $query = "select * from val.ff_anular_transaccion('" . $id . "', '" . $day . "', '" . $year . "', '" . $user . "','" . $type . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetValueById($valueCode)
    {
        $query = "select * from val.valores a where a.cod_val = '" . $valueCode . "'";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * TE2. Obtiene las transacciones de un valor que se vende de acuerdo a un rango de fechas
    public static function GetValueTransactionsById($valueCode, $initialDate, $finalDate)
    {
        $query = "select * from val.tra_dia a inner join val.tip_tra b on a.tip_tra = b.tip_tra " .
            "where  a.cod_val = '" . $valueCode . "' and a.fec_tra >= '" . $initialDate . "'::date and a.fec_tra <='" . $finalDate . "'::date ";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * TE4. Obtiene la lista de valores asignados a un usuario
    public static function GetUserValues($id)
    {
        //SELECT * FROM bval.ff_transacciones_persona('6600648')
        $query = "SELECT * FROM bval.ff_valores_usuario('" . $id . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * TE5. Obtiene las cuotas realizadas para un valorado    
    public static function getGroupValueTransactionsByCode($valueCode, $year)
    {
        $query = "select * from  bval.ff_transacciones_agrupadas('" . $valueCode . "','" . $year . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * TE6. Obtiene las cuotas realizadas para un valorado detallado por persona
    public static function getSingleValueTransactionsByCode($valueCode, $dni)
    {
        $query = "select * from  bval.ff_transacciones_detalladas('" . $valueCode . "','" . $dni . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetStudentSalesDay($description, $user, $year)
    {
        $user  = 'nottingham';
        $query = "select * from val.diario d where d.usr_cre = '" . $user . "' and d.gestion = '" . $year . "' order by d.id_dia desc";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
