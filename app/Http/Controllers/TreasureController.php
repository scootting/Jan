<?php

namespace App\Http\Controllers;

use App\Libraries\JSRClient;
use App\Treasure;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TreasureController extends Controller
{

    //  * T2. Obtener una lista de las ventas en linea solicitadas durante la gestion.
    //  * {year: año de ingreso}
    public function getSaleInLine(Request $request)
    {
        $year = $request->get('year');
        $data = Treasure::GetSaleInLine($year);
        return json_encode($data);
    }

    //  * T3. Obtener la lista de solicitudes bancarias en estado solicitado.
    //  * {year: año de ingreso}
    public function getSaleInLineDetail(Request $request)
    {
        $id = $request->get('id'); // '' cadena vacia
        $usuario = $request->get('user');
        $gestion = $request->get('year');
        \Log::info("esta es la gestion: " . $gestion);
        $data = Treasure::GetSaleInLineDetail($gestion);

        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;

        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getDaysOfSale')]
        );
        return json_encode($paginate);

    }

    //  * T4. Obtener el detalle de una solicitud utilizando su id.
    //  * {request: id de la solicitud }
    public function getDataRequestById(Request $request)
    {
        $id_request = $request->get('id');
        \Log::info($id_request);
        $data = Treasure::GetDataRequestById($id_request);
        $detail = Treasure::GetDetailRequestById($id_request);
        $boucher = Treasure::getBoucherRequestById($id_request);
        $extract = Treasure::getExtractBankById($id_request);
        return json_encode(['data' => $data, 'detail' => $detail, 'boucher' => $boucher, 'extract' => $extract]);
    }

    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad y el año de ingreso.
    //  * {id: numero de carnet de identidad}
    //  * {year: año de ingreso}
    public function getDataOfStudentById(Request $request)
    {
        $id = $request->get('id');
        $year = $request->get('year');
        $data = Treasure::getDataOfStudentById($id, $year);
        return json_encode($data);
    }

    //  * Encontrar a un estudiante nuevo a traves de su carnet de identidad y el año de ingreso.
    //  * {description: descripcion del valor}
    //  * {year: año de los valores}
    public function getValuesProcedure(Request $request)
    {
        $id_modalidad = $request->get('id');
        $ci_per = $request->get('ci_per');
        $year = $request->get('year');
        switch ($id_modalidad) {
            case 1: //EXAMEN PSA
            case 2: //CURSO PREUNIVERSITARIO
            case 3: //CASE 2DA PSA
            case 116: //EXAMEN PSA ADMISION ESPECIAL
            case 5: //INGRESO DIRECTO
            case 20: //3ra PSA
            case 13: //TRASPASO
            case 101://EXAMEN P.S.A. - R027/2022                                       
                $description = 'NUEVOS';
                break;
            case 39: //CODEMETROP
                $description = 'CONVENIO_MEDICO';
                break;
            case 8: //profesional
                $description = 'PROFESIONAL';
                break;
            case 46: //DEFENSA CIVIL
                $description = 'DEFENSA_CIVIL';
                break;
            case 10: //simultanea
                $description = 'SIMULTANEA';
                break;
            case 9: //CAOB
            case 42: //ORIGINARIA 43
            case 41: // por convenio
            case 43: //capacidades especiales
            case 36: //traspaso de universidades
                $description = 'ORIGINARIA';
                break;
            case 32: //OLIMPIADAS
            case 6: //ADMISION POR EXCELENCIA ACADEMICA
            case 7: //ADMISION EXTRAORDINARIA DEPORTIVA
                $description = 'EXCELENCIA';
                break;
            default:
                $description = 'SIN_TRAMITE';
        }
        /*
        10461608
        $description = 'SIMULTANEA';
         */
        $data = Treasure::getValuesProcedure($description, $year);
        return json_encode($data);
    }

    //reportes usando Jasper
    public function getReportValuesQr(Request $request)
    {
        $id_dia = $request->get('id_dia');
        $ci_per = $request->get('ci_per');
        $gestion = $request->get('gestion');
        $usr_cre = $request->get('usr_cre');
        $nreport = 'test_1';
        $controls = array(
            'p_id_dia' => $id_dia,
            'p_ci_per' => trim($ci_per),
            'p_gestion' => $gestion,
            'p_usr_cre' => trim($usr_cre),
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    public function getReportDetailStudents(Request $request)
    {
        $id_dia = $request->get('id_dia');
        $gestion = $request->get('gestion');
        $usr_cre = $request->get('usr_cre');
        \Log::info('estos son datos para el reporte general: ' . $id_dia . ' ' . $gestion . ' ' . trim($usr_cre));
        $nreport = 'test_general_1';
        //$nreport = 'test_details_1';
        $controls = array(
            'p_id_dia' => $id_dia,
            'p_gestion' => $gestion,
            'p_usr_cre' => trim($usr_cre),
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;

    }

    public function storeTransactionsByStudents(Request $request)
    {
        $id_tran = 0;
        $dataDayTransactions = $request->get('dayTransactions');
        $dataPostulations = $request->get('postulations');
        $dataValuesPostulations = $request->get('valuesPostulations');

        $id_dia = $dataDayTransactions['id_dia'];
        $fec_tra = $dataDayTransactions['fec_tra'];
        $usr_cre = $dataDayTransactions['usr_cre'];
        $gestion = $dataDayTransactions['gestion'];

        $ci_per = strtoupper($dataPostulations['nro_dip']);
        $nombres = strtoupper($dataPostulations['nombres']);
        $paterno = strtoupper($dataPostulations['paterno']);
        $materno = strtoupper($dataPostulations['materno']);
        $des_tra = strtoupper($dataPostulations['modalidad']);

        /*
        $idx = Treasure::getIdTransactionsByYear($gestion);
        $idx = $idx[0]->{'ff_id_tramite'};
        $nro_com = str_pad($idx, 6, "0", STR_PAD_LEFT);
         */
        $tip_tra = '10';

        if ($paterno != "") {
            $des_per = $paterno . " " . $materno . "," . $nombres;
        } else {
            $des_per = $materno . "," . $nombres;
        }

        foreach ($dataValuesPostulations as $item) {
            # code...
            $cod_val = $item['cod_val'];
            $can_val = $item['can_val'];
            $pre_uni = $item['pre_uni_val'];
            $imp_val = $item['imp_val'];
            //$imp_val = $can_val * $pre_uni;
            if ($imp_val == 1) {
                $marker = Treasure::addTransactionsByStudents($id_dia, $cod_val, $can_val, $pre_uni, $fec_tra, $usr_cre, '-1', $ci_per, $des_per, $tip_tra, $gestion);
                $id_tran = $marker[0]->{'id_tran'};
            }
            $data = Treasure::addProcedureByStudents($id_dia, $id_tran, -1, $cod_val, $ci_per, $des_per, -1, $gestion, $des_tra, $pre_uni);
            $id_tran = 0;
        }
        return json_encode($marker);
    }

    public function getSaleOfDaysByDescription(Request $request)
    {
        $descripcion = $request->get('description'); // '' cadena vacia
        $usuario = $request->get('user');
        $gestion = $request->get('year');
        $data = Treasure::getSaleOfDaysByDescription($descripcion, $usuario, $gestion);

        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;

        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getDaysOfSale')]
        );
        return json_encode($paginate);
    }

    public function getSaleOfDayById(Request $request)
    {
        $id = $request->get('id'); // '' cadena vacia
        $usuario = $request->get('user');
        $gestion = $request->get('year');
        $data = Treasure::getSaleOfDayById($id, $usuario, $gestion);
        return json_encode($data);
    }

    public function addSaleOfDay(Request $request)
    {
        $usuario = $request->get('user');
        $gestion = $request->get('year');
        $data = Treasure::addSaleOfDay($usuario, $gestion);
        return json_encode($data);
    }

    public function getValueById(Request $request)
    {
        $valor = $request->get('id');
        $gestion = $request->get('year');
        $data = Treasure::getValueById($valor, $gestion);
        return json_encode($data);
    }

    //  * T1. Buscar transacciones hechas por una persona a traves de su carnet de identidad.
    //  * {id: numero de carnet de identidad}
    public function getTransactionsByPerson(Request $request)
    {
        $id = $request->get('id'); // '' cadena vacia
        $data = Treasure::getTransactionsByPerson($id);
        return json_encode($data);
    }

    //  * Buscar transacciones realizadas en la gestion buscada.
    //  * {year: gestion}
    public function getAllTransactionsByYear(Request $request)
    {
        \Log::info($request);

        $year = $request->get('year'); // '' cadena vacia
        $description = strtoupper($request->get('description')); // '' cadena vacia
        $data = Treasure::GetAllTransactionsByYear($description, $year);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;

        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getAllTransactionsByYear')]
        );
        return json_encode($paginate);

    }

    public function cancelTransactionById(Request $request)
    {

        $transaccion = $request->get('transaccion');
        $id = $transaccion['id_tran'];
        $day = $transaccion['id_dia'];
        $year = $request->get('gestion');
        $user = $request->get('usuario'); // '' cadena vacia
        $type = $request->get('tipo');
        $data = Treasure::CancelTransactionById($id, $day, $year, $user, $type);
        return null;
        return json_encode($data);
    }
}
