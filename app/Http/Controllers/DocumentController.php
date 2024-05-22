<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class DocumentController extends Controller
{

    //  * EF1. Obtener la lista de documentos subidos para cada estado financiero
    // Route::post('getFinancialStatementDetailbyId', 'DocumentController@getFinancialStatementDetailbyId');
    //  * EF1. Obtener la lista de estados financieros
    public function getFinancialStatements(Request $request)
    {
        $year = $request->get('year');
        $data = Document::GetFinancialStatements($year);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getRequestsMemorial')]
        );
        return json_encode($paginate);
    }
    //  * EF2. Obtener la lista de documentos por estado financiero
    public function getDocumentsbyFinalcialStatemet(Request $request)
    {
        $id = $request->get('id');
        $gestion = $request->get('gestion');
        $data = Document::GetDocumentsbyFinalcialStatemet($id, $gestion);
        return json_encode($data);
    }

    //  * EF3. Guarda los documentos digitalizados
    public function storeDigitalDocument(Request $request)
    {
        $id = $request->get('idx');
        $description = $request->get('descripcion');
        $id_state = $request->get('id');
        $fileExt = $request->file('file')->getClientOriginalExtension();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data = file_get_contents($file);
            $escaped = pg_escape_bytea($data);
            $data = Document::StoreDigitalDocument($id, $description, $id_state, $escaped);
        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        return response()->json(['success' => 'Uploaded Successfully.']);
    }

    //  * EF4. Obtener documentos digitalizados
    public function getDigitalFinancialDocument(Request $request)
    {
        $id = $request->get('id');
        $year = $request->get('year');
        $result = Document::getDigitalFinancialDocument($id, $year);
        if (!empty($result[0]->pdf_data)) {
            $my_bytea = stream_get_contents($result[0]->pdf_data);
            return $my_bytea;
        } else {
            return response()->json([
                'error' => 'No se encontró ningún registro con el ID proporcionado.',
            ]);
        }
    }
    //  * EF5. Elimina un documento mal subido
    public function setDeleteDigitalDocument(Request $request){
        $id = $request->get('id');
        $year = $request->get('year');
        $data  = Document::SetDeleteDigitalDocument($id, $year);
        return json_encode($data);
    }

    //  * ME1. Obtener la lista de memoriales para su verificacion
    public function getRequestsMemorial(Request $request)
    {
        $year = $request->get('year');
        $data = Document::getRequestsMemorial($year);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getRequestsMemorial')]
        );
        return json_encode($paginate);
    }

    //  * ME2. Obtener la lista de memoriales para su verificacion
    public function getRequestMemorialById(Request $request)
    {
        $id = $request->get('id');
        $gestion = $request->get('gestion');
        $data = Document::getRequestMemorialById($id, $gestion);
        return json_encode($data);
    }

    //  * ME3. Imprimir el memorial seleccionado
    public function reportRequestMemorial(Request $request)
    {
        $nro_com = $request->get('voucher');
        $tip_tra = $request->get('tipo');
        $gestion = $request->get('gestion'); //$dataDays['gestion'];
        \Log::info("DATOS PARA LA IMPRESION DE BOUCHER");
        \Log::info($gestion);
        \Log::info($tip_tra);
        \Log::info($nro_com);

        $nreport = 'DetailCreditSaleLetter';
        $controls = array(
            'p_nro_com' => trim($nro_com),
            'p_tip_tra' => $tip_tra,
            'p_gestion' => $gestion,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }

    // *** - añadir certificado de los diplomados - ***
    // *** - parametros [carnet de identidad, ] - ***
    public static function addGraduateCertficate(Request $request)
    {
        $nro_doc = $request->get('nro_doc');
        $fec_memo = $request->get('fec_memo');
        $fec_prov = $request->get('fec_prov');
        $no_prov = $request->get('no_prov');
        $ci_per = $request->get('ci_per');
        $des_per = $request->get('des_per');
        $cod_val = $request->get('cod_val');
        $des_dip = $request->get('des_dip');
        $usr_cre = $request->get('usr_cre');
        $gestion = $request->get('gestion');
        $data = Document::AddGraduateCertficate($nro_doc, $fec_memo, $fec_prov, $no_prov, $ci_per, $des_per, $cod_val, $des_dip, $usr_cre, $gestion);
        return $data;
    }
    // *** - verificar certificado de los diplomados - ***
    // *** - parametros [numero de certificado, gestion actual, carnet de identidad del verificante ] - ***
    public static function checkGraduateCertficate(Request $request)
    {
        $nro_doc = $request->get('nro_doc');
        $gestion = $request->get('gestion');
        $ci_auth = $request->get('ci_auth');
        $data = Document::checkGraduateCertficate($nro_doc, $ci_auth, $gestion);
        return $data;
    }

    // *** - obtener los cursos ofrecidos por postgrado - ***
    // *** - parametros [] - ***
    public static function getPostGraduateCourses()
    {
        $query = "SELECT id, cod_val, des_dip, act_dip, fec_cre, usr_cre," .
            "(SELECT v.pre_uni FROM val.valores v WHERE v.cod_val = b.cod_val) AS imp_val" .
            "FROM bdoc.val_mat b WHERE b.act_dip = TRUE";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - obtener los documentos por oficina  de los usuarios correspondiente - ***
    // *** - parametros [] - ***
    public static function getDocumentsByOffice(Request $request)
    {
        $descripcion = $request->get('description');
        $soa = $request->get('soa');
        $gestion = $request->get('year');
        $data = Document::getDocumentsByOffice($descripcion, $soa, $gestion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 10;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/persons')]
        );
        return json_encode($paginate);
    }
}
