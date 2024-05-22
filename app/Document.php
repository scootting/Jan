<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Document extends Model
{
    //  * EF1. Obtener la lista de estados financieros
    public static function GetFinancialStatements($year)
    {
        $query = "SELECT * FROM efe.estados e order by gestion desc";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * EF2. Obtener la lista de documentos
    public static function GetDocumentsbyFinalcialStatemet($id, $year)
    {
        $query = "SELECT id, idx, descripcion, estado FROM efe.estado_detalle d WHERE d.id_estado ='" . $id . "' order by idx desc";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * EF3. Guarda los documentos digitalizados
    public static function StoreDigitalDocument($id, $description, $id_state, $escaped)
    {
        $query = "INSERT INTO efe.estado_detalle(idx, descripcion, id_estado, digitalizado) VALUES (" . $id . ",'" . $description . "'," . $id_state . ", '{$escaped}')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * EF4. Obtener documentos digitalizados
    public static function getDigitalFinancialDocument($id, $year)
    {
        $query = "SELECT digitalizado as pdf_data FROM efe.estado_detalle d WHERE d.id = ?";
        \Log::info($id);        
        \Log::info($query);        
        $data = DB::select($query, [$id]);
        return $data;
    }

    //  * EF5. Elimina un documento mal subido
    public static function SetDeleteDigitalDocument($id, $year)
    {
        $query = "DELETE FROM efe.estado_detalle d WHERE d.id = ?";
        \Log::info($id);        
        \Log::info($query);        
        $data = DB::select($query, [$id]);
        return $data;
    }


    //  * ME1. Obtener la lista de memoriales para su verificacion
    public static function GetRequestsMemorial($year)
    {
        $query = "SELECT * FROM bdoc.documentos d WHERE d.gestion ='" . $year . "' order by fecha, idx desc";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * M1. Obtener la lista de memoriales para su verificacion
    public static function GetRequestMemorialById($id, $gestion)
    {
        $query = "select * from public.personas p inner join bdoc.diario d "+
            "on p.nro_dip = d.ci_per where d.id ='" . $id . "' and d.gestion = '2023'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - añadir certificado de los diplomados - ***
    // *** - parametros [carnet de identidad, ] - ***
    public static function AddGraduateCertficate($nro_doc, $fec_memo, $fec_prov, $no_prov, $ci_per, $des_per, $cod_val, $des_dip, $usr_cre, $gestion)
    {
        $query = "INSERT INTO bdoc.cer_dia(no_doc, fec_memo, fec_prov, no_prov, ci_per, des_per, cod_val, des_dip, usr_cre, gestion)" .
            "VALUES('" . $nro_doc . "','" . $fec_memo . "','" . $fec_prov . "','" . $no_prov . "','" . $ci_per . "','" . $des_per . "','" . $cod_val . "','" . $des_dip . "','" . $usr_cre . "','" . $des_per . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    // *** - verificar certificado de los diplomados - ***
    // *** - parametros [carnet de identidad, ] - ***
    public static function CheckGraduateCertficate($nro_doc, $ci_auth, $gestion)
    {

        $query = "UPDATE bdoc.cer_dia SET ci_auth = '" . $ci_auth . "',est_doc = 'Verificado'" .
            "WHERE nro_doc='848' AND gestion ='2021'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *** - obtener los cursos ofrecidos por postgrado - ***
    // *** - parametros [] - ***
    public static function GetPostGraduateCourses()
    {
        $query = "SELECT id, cod_val, des_dip, act_dip, fec_cre, usr_cre," .
            "(SELECT v.pre_uni FROM val.valores v WHERE v.cod_val = b.cod_val) AS imp_val" .
            "FROM bdoc.val_mat b WHERE b.act_dip = TRUE";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function getDocumentsByOffice($descripcion, $soa, $gestion)
    {
        //select * from bdoc.ff_buscar_documentos_soa('', '00000012', '2021')
        $query = "select * from bdoc.ff_buscar_documentos_soa('', '00000012', '2021')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
