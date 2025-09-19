<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class FixedAsset extends Model
{
    //
    //  *  AF10. Obtiene la informacion necesaria para crear un documento de entrega
    //  *  Tipos de documentos
    public static function GetTypesOfDocument($year)
    {
        $query = "select * from actx.tipo d order by d.des_tipo";
        \Log::info($query);
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF10. Obtiene la informacion necesaria para crear un documento de entrega
    //  *  Tipos de cargo
    public static function GetPositionsOfDocument($year)
    {
        $query = "select * from actx.cargo d order by d.des_cargo";
        \Log::info($query);
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF10. Obtiene la informacion necesaria para crear un documento de entrega
    //  *  Tipos de categoria programatica
    public static function GetCategoryProgramaticsOfDocument($year)
    {
        $query = "select * from actx.categoria_programatica d order by d.des_prg";
        \Log::info($query);
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF11. Obtiene la informacion necesaria para crear un documento de entrega
    public static function StoreDataAssignment($cod_tipo, $fecha, $cod_prg, $des_prg, $ci_resp, $ci_elab, $des_elab, $gestion, $des_resp, $ref_prg, $id_cargo)
    {
        $query = "select * from actx.ff_registrar_asignacion('" . $cod_tipo . "','" . $fecha . "','" . $cod_prg . "','" . $des_prg . "','" . $ci_resp . "','" . $ci_elab . "','" . $des_elab
            . "'," . $gestion . ",'" . $des_resp . "','" . $ref_prg . "'," . $id_cargo . ")";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF12. Obtiene la lista de documentos    
    public static function GetDataAssignments($gestion)
    {
        $query = "select *, a.id as id_asignacion, b.id as id_tipo from actx.asignaciones a inner join actx.tipo b on a.cod_tipo = b.cod_tipo where gestion = '" . $gestion . "' order by gestion, idx desc";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF13. Obtiene la informacion necesaria para crear activos fijos dentro de un documento       
    public static function GetDataAssignmentsById($id_asignacion, $gestion)
    {
        $query = "select * from actx.asignaciones where gestion ='" . $gestion . "' and id = " . $id_asignacion . "";
        \Log::info($query);
        //$query = "select * from actx.ff_datos_asignacion_id('" . $id_asignacion . "'," . $cod_tipo . ",'" . $gestion . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF13. Obtiene la informacion necesaria para crear activos fijos dentro de un documento       
    public static function GetMeasurement($year)
    {
        //unidad de medida
        $query = "SELECT * from com.f_unidad_medida()";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF13. Obtiene la informacion necesaria para crear activos fijos dentro de un documento       
    public static function GetAccountingItem($year)
    {
        //partida contable
        $query = "SELECT con_cod as id_contable, con_des as des_contable FROM act.f_b_contable()";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF13. Obtiene la informacion necesaria para crear activos fijos dentro de un documento       
    public static function GetDataAssignmentsDetails($id)
    {

        $query = "select id, idx, fecha_adquisicion,id_activo, codigo, des_marca,des_modelo,descripcion, medida, cantidad, importe, accesorios as adicional, " .
            "id_contable, des_contable, id_presupuesto, des_presupuesto, estado from actx.asignaciones_detallada where id_asignacion = '" . $id . "' order by idx desc";
        $data = collect(DB::select(DB::raw($query)));

        $data->transform(function ($item) {
            $item->adicional = json_decode($item->adicional, true); // Convertir JSONB a array
            return $item;
        });
        // Convertimos el campo JSONB en array asociativo para cada fila
        /*
        $data = $data->map(function ($item) {
            return [
                'idx' => $item->idx,
                'fecha_adquisicion' => $item->fecha_adquisicion,
                'des_marca' => $item->des_marca,
                'des_modelo' => $item->des_modelo,
                'descripcion' => $item->descripcion,
                'medida' => $item->medida,
                'cantidad' => $item->cantidad,
                'importe' => $item->importe,
                'adicional' => json_decode($item->accesorios, true), // Convertir JSONB a array
                'id_contable' => $item->id_contable,
                'des_contable' => $item->des_contable,
                'id_presupuesto' => $item->id_presupuesto,
                'des_presupuesto' => $item->des_presupuesto,
                'estado' => $item->estado,
            ];
        });*/
        return $data;
    }

    //  *  AF13. Obtiene la informacion necesaria para crear activos fijos dentro de un documento       
    public static function GetBudgetItem($year)
    {
        //partida presupuestaria
        $query = "select * from act.f_b_partida('" . $year . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF14. Guarda la informacion necesaria para crear activos fijos dentro de un documento       
    public static function RemoveDataAssignmentDetails($id)
    {
        $query = "delete from actx.asignaciones_detallada a where a.id_asignacion = " . $id . "";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF25. Quita un activo fijo de la revaluacion     
    //Route::post('setRemoveDataAssignmentDetails/', 'FixedAssetController@setRemoveDataAssignmentDetails');
    public static function SetRemoveDataAssignmentDetails($id)
    {
        $query = "delete from actx.asignaciones_detallada a where a.id = " . $id . "";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF14. Guarda la informacion necesaria para crear activos fijos dentro de un documento       
    public static function StoreDataAssignmentDetails($id_documento, $indice, $codigo, $marca, $modelo, $descripcion, $medida, $cantidad, $importe, $fecha_adquisicion, $id_contable, $des_contable, $id_presupuesto, $des_presupuesto, $estado, $adicional, $usuario)
    {
        $query = "select * from actx.ff_registrar_asignacion_detallada(" . $id_documento . "," . $indice . ",'" . $codigo . "','" . $marca . "','" . $modelo . "','" . $medida . "'," . $cantidad . "," . $importe
            . ",'" . $fecha_adquisicion . "'," . $id_contable . ",'" . $des_contable . "'," . $id_presupuesto . ",'" . $des_presupuesto . "','" . $estado
            . "','" . $adicional . "','" . $descripcion . "','" . $usuario . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF14. Actualizar la informacion necesaria para crear activos fijos dentro de un documento       
    public static function UpdateDataAssignmentDetails($id_activo, $id_documento, $indice, $codigo, $marca, $modelo, $descripcion, $medida, $cantidad, $importe, $fecha_adquisicion, $id_contable, $des_contable, $id_presupuesto, $des_presupuesto, $estado, $adicional, $usuario)
    {
        $query = "select * from actx.ff_actualizar_asignacion_detallada(" . $id_activo . "," . $id_documento . "," . $indice . ",'" . $codigo . "','" . $marca . "','" . $modelo . "','" . $medida . "'," . $cantidad . "," . $importe
            . ",'" . $fecha_adquisicion . "'," . $id_contable . ",'" . $des_contable . "'," . $id_presupuesto . ",'" . $des_presupuesto . "','" . $estado
            . "','" . $adicional . "','" . $descripcion . "','" . $usuario . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF15. Verificar un documento       
    public static function VerifyDataAssignmentDetails($id_documento, $tipo_documento, $usuario, $gestion)
    {
        $query = "select * from actx.ff_verificar_asignacion(" . $id_documento . ",'" . $tipo_documento . "','" . $usuario . "','" . $gestion . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF16. Obtiene los activos registrados dentro de un documento       
    public static function GetDataFixedAsssetDetails($id)
    {

        $query = "select id_asignacion, codigo, codigo_anterior, idx, fecha_adquisicion,des_marca,des_modelo,descripcion, medida, cantidad, importe, accesorios as adicional, " .
            "id_contable, des_contable, id_presupuesto, des_presupuesto, estado, ci_resp from actx.activosx where id_asignacion in (select id from actx.asignaciones_detallada where id_asignacion = '" . $id . "') order by idx desc";
        $data = collect(DB::select(DB::raw($query)));

        $data->transform(function ($item) {
            $item->adicional = json_decode($item->adicional, true); // Convertir JSONB a array
            return $item;
        });
        return $data;
    }

    public static function GetDocumentFixedAssetByYear($year, $type)
    {
        //$year = 2020;
        //$query = "select * from act.asignaciones aa where aa.tip_doc = '".$type."' and aa.gestion = '".$year."' and aa.estado = 'Verificado' order by aa.fec_cre desc";
        $query = "select * from act.asignaciones aa where aa.tip_doc in (1) and aa.gestion = '" . $year . "' and aa.estado = 'Verificado' order by aa.fec_cre desc";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //
    public static function getFixedAssetsbyDocument($document)
    {
        $query = "SELECT * FROM act.activos aa WHERE aa.codigo LIKE '" . $document . "%'";
        \Log::info("esta es una consulta mas: " . $query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetCategoryProgramatic($year, $description)
    {
        if ($description != "") {
            $query = "select cat_des as des_prg, cod_prg from public.sis_cat_pro a where a.cat_pro in ('00', '01', '02', '03', '10', '51', '61') and a.cat_sis = 'ACTIVIDAD' and a.cat_ano = " . $year . " and a.cat_des like '%" . $description . "%'";
            \Log::info("prueba: " . $query);
        } else {
            $query = "select cat_des as des_prg, cod_prg from public.sis_cat_pro a where a.cat_pro in ('00', '01', '02', '03', '10', '51', '61') and a.cat_sis = 'ACTIVIDAD' and a.cat_ano = " . $year . "";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF1. Obtiene la informacion necesaria para crear un documento de entrega
    //  *  Tipos de documentos
    public static function GetDataTypesAssignments($year)
    {
        $query = "select * from actx.tipo d";
        \Log::info($query);
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF1. Obtiene la informacion necesaria para crear un documento de entrega
    //  *  Categorias programaticas
    public static function GetDataPrograms($year)
    {
        $query = "select *, cat_des as value from public.sis_cat_pro d where d.cat_ano = '" . $year . "' and d.cat_sis = 'ACTIVIDAD'";
        \Log::info($query);
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AC2. Obtiene la lista de categorias programaticas    
    //  * {year: gestion en la que se desarrolla}
    public static function GetAssignments($description, $typea, $year)
    {
        $query = "select * from actx.ff_datos_asignacion('" . $description . "','" . $typea . "','" . $year . "')";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AC4. Obtiene la lista de asignaciones detallado
    public static function GetSearchFixedAssets($description, $year)
    {
        $query = "select * from actx.ff_datos_recopilatorio('" . $description . "','" . $year . "')";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AC4. Obtiene la lista de asignaciones detallado
    public static function GetFixedAssetsAssignment($id)
    {
        $query = "select * from actx.activos a where a.id_asignaciones = " . $id . "";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetAditionalFixedAssetsAssignment($id)
    {
        $query = "select * from actx.activos_adicional b where b.id_activo in (select id from actx.activos a where a.id_asignaciones = " . $id . ")";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AC8. Obtiene la lista de asignaciones detallado por activo
    public static function GetFixedAssetsById($id, $year)
    {
        $query = "select * from actx.ff_datos_activo(" . $id . ", '" . $year . "')";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function GetAditionalFixedAssetsById($id)
    {
        $query = "select * from actx.activos_adicional b where b.id_activo =" . $id . "";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function StoreActiveFixed2($codigo, $codigo_anterior, $idx, $descripcion, $medida, $cantidad, $importe, $fecha_adquisicion, $id_contable, $id_presupuesto, $estado, $cod_prg, $des_prg, $ci_resp, $id_asignaciones)
    {
        $query = "select * from actx.ff_registrar_activos('" . $codigo . "','" . $codigo_anterior . "'," . $idx . ",'" . $descripcion .
            "','" . $medida . "'," . $cantidad . "," . $importe . ",'" . $fecha_adquisicion .
            "'," . $id_contable . ",'" . $id_presupuesto . "'," . $estado . ",'" . $cod_prg .
            "','" . $des_prg . "','" . $ci_resp . "'," . $id_asignaciones . ")";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function StoreActiveFixedAditional($a_cantidad, $a_descripcion, $a_serial, $id_activo)
    {
        $query = "INSERT INTO actx.activos_adicional(cantidad, descripcion, serial, id_activo) VALUES " .
            "('" . $a_cantidad . "','" . $a_descripcion . "','" . $a_serial . "'," . $id_activo . ") RETURNING id";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF20. Obtiene un activo fijo de la lista de actualizaciones y depreciaciones
    public static function GetDataFixedAssetDetails($description, $year)
    {
        $query = "select * from actx.ff_datos_recopilatorio('" . $description . "','" . $year . "')";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  A23. Obtiene la lista de un id de asignaciones detallado
    public static function GetFixedAssetsAssignmentDetails($id)
    {
        $query = "select * from actx.asignaciones_detallada a where a.id = " . $id . "";
        $data  = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  *  A23. Obtiene la lista de un id de asignaciones detallado
    public static function GetFixedAssetsAssignmentDetails2($id)
    {
        $query = "select * from actx.asignaciones_detallada a where a.id = " . $id . "";
        $data  = collect(DB::select(DB::raw($query)));
        $data->transform(function ($item) {
            $item->accesorios = json_decode($item->accesorios, true); // Convertir JSONB a array
            return $item;
        });
        return $data;
    }

    //  *  AF26. Obtiene un activo fijo de la lista de actualizaciones y depreciaciones por su id
    //Route::post('getDataFixedAssetDetailsById/', 'FixedAssetController@getDataFixedAssetDetailsById');
    public static function GetDataFixedAssetDetailsById($id, $year)
    {
        $query = "select * from actx.ff_datos_recopilatorio_por_id(" . $id . ",'" . $year . "')";
        //$query = "select *, id_programa as cod_prg, programa as cat_des, programa as value from bdoc.adicional d where d.gestion = '" . $year . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * AF30. Selecciona las imagenes cargadas del activo
    public static function getImagenByRevaluedAssignment($id)
    {
        $query = "SELECT id, id_activo, descripcion FROM actx.revaluo_imagen d WHERE d.id_activo ='" . $id . "' order by id desc";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;

    }
    //  * AF29. Guarda las imagenes digitalizadas
    public static function StoreDigitalDocument($id, $description, $escaped)
    {
        $query = "INSERT INTO actx.revaluo_imagen(id_activo, descripcion, imagen) VALUES (" . $id . ",'" . $description . "', '{$escaped}')";
        //\Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    public static function GetDigitalAssetById($id)
    {
        $query = "SELECT imagen as pdf_data FROM actx.revaluo_imagen d WHERE d.id = ?";
        $data  = DB::select($query, [$id]);
        return $data;
    }

    //  *  AF30. Guardar los analisis del auditor despues de su evaluacion.
    public static function StoreDataAuditorDetails($id, $actualizacion, $seguridad, $proteccion, $capacitacion)
    {
        $query = "select * from actx.ff_registrar_detalle_auditor(" . $id . ",'" . $actualizacion . "','" . $seguridad . "','" . $proteccion . "','" . $capacitacion . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF32. Eliminar la imagen subida del activo fijo.
    public static function deleteDigitalAssetById($id)
    {
        $query = "DELETE FROM actx.revaluo_imagen d WHERE d.id = ?";
        $data  = DB::select($query, [$id]);
        return $data;
    }

    public static function GetFixedAssetsRevalued($id)
    {
        $query = "select * from actx.asignaciones_revaluo a where a.id_activo = " . $id . "";
        $data  = collect(DB::select(DB::raw($query)));
        $data->transform(function ($item) {
            $item->cotizaciones = json_decode($item->cotizaciones, true); // Convertir JSONB a array
            return $item;
        });

        return $data;
    }

    //  *  AF14. Guarda la informacion necesaria para crear activos fijos dentro de un documento
    public static function StoreDataRevaluedDetails($indice, $id_contable, $perdida, $funcionalidad, $obsolescencia, $conservacion, $uso, $mantenimiento, $cotizaciones, $usuario)
    {
        $query = "select * from actx.ff_registrar_revaluo_detallado(" . $indice . "," . $id_contable . "," . $perdida . "," . $funcionalidad . "," . $obsolescencia .
            "," . $conservacion . "," . $uso . "," . $mantenimiento . ",'" . $cotizaciones . "','" . $usuario . "')";

        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  *  AF14. Guarda la informacion necesaria para crear activos fijos dentro de un documento
    public static function UpdateDataRevaluedDetails($indice, $id_contable, $perdida, $funcionalidad, $obsolescencia, $conservacion, $uso, $mantenimiento, $cotizaciones, $usuario)
    {
        $query = "select * from actx.ff_actualizar_revaluo_detallado(" . $indice . "," . $id_contable . "," . $perdida . "," . $funcionalidad . "," . $obsolescencia .
            "," . $conservacion . "," . $uso . "," . $mantenimiento . ",'" . $cotizaciones . "','" . $usuario . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

}
