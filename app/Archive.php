<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Archive extends Model
{
    //
    //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
    //  * parametros [description: descripcion que se esta buscando ]
    public static function GetArchivesByDescription($description, $type)
    {
        if ($description == '') {
            $query = "select d.id, d.id_doc, d.fecha, d.glosa, d.gestion, d.estado, e.descr from arch.doc d inner join arch.tipos e on d.id_arch = e.id where id_tipo = '" . $type . "' order by id_doc desc";
        } else {
            $query = "select d.id, d.id_doc, d.fecha, d.glosa, d.gestion, d.estado, e.descr from arch.doc d where inner join arch.tipos e on d.id_arch = e.id d.glosa like '%" . $description . "%' and id_tipo = '" . $type . "' order by id_doc desc";
        }
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
    //  * parametros {id: identificador del archivo, year: gestion del archivo }
    public static function GetDocumentsbyArchive($archive, $year)
    {
        //SELECT * FROM arch.ff_documentos_archivo('00000001', '2019')
        $query = "SELECT * FROM arch.ff_documentos_archivo('" . $archive . "','" . $year . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * A3. Obtiene la lista de tipos de documentos que pertenecen a un archivo
    //  * parametros {description: descripcion del tipo de documento que se necesita }
    public static function getTypesDocument($type)
    {
        if ($type == '') {
            $query = "select * from arch.tipos d order by d.idc desc";
        } else {
            //$query = "select * from arch.tipos t where t.tipo = '" . $description . "'";
            $query = "select * from arch.tipos d where idt ='" . $type . "' order by d.descr asc";
        }
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A6. Obtiene la lista de contenedores para archivar con una breve descripcion
    //  * parametros [description: descripcion que se esta buscando ]
    public static function getFileContainerByDescription($description)
    {
        if ($description == '') {
            $query = "select *, (select t.descr from arch.tipos t where t.id = c.id_tipo) as descr from arch.conte c";
        } else {
            $query = "select *, (select t.descr from arch.tipos t where t.id = c.id_tipo) as descr from arch.conte c where c.des_con like '%" . $description . "%'";
        }

        \Log::info("Esta es la consulta de archivos: " . $query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A7. Obtiene la lista de contenedores para archivar con una breve descripcion
    //  * parametros [glosa: descripcion del documento, tipo: archivo, documento, ubicacion ]
    public static function StoreFileContainer($id_tipo, $glosa, $fecha, $id_arch, $gestion, $id_doc)
    {
        $query = "select * from arch.ff_registrar_contenedor('" . $id_tipo . "','" . $glosa . "','" . $fecha . "','" . $id_arch . "','" . $gestion . "','" . $id_doc . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A8. Obtiene un tipo de archivo en especifico
    public static function GetTypeArchiveById($id)
    {
        $query = "select * from arch.tipos d where d.id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * A9. Actualiza la informacion de un tipo de archivo
    public static function OnUpdateTypeArchive($id, $descripcion)
    {
        $query = "UPDATE arch.tipos set descr = '" . $descripcion . "' where id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A10. Guardar un nuevo tipo de archivo
    public static function OnStoreTypeArchive($descripcion, $tipo)
    {
        $query = "SELECT * FROM arch.ff_registrar_tipo('" . $descripcion . "','" . $tipo . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A11. Guardar los archivos del documento
    public static function addArchivesByDocument($documento, $indice, $numeral, $glosa, $fecha, $id_tipo, $id_arch, $gestion)
    {
        //$query = "SELECT * FROM arch.ff_registrar_detalle_documento('" . $descripcion . "','" . $tipo . "')";
        $query = "INSERT INTO arch.doc2(id_doc, numeral, indice, glosa, fecha, id_arch, gestion, id_tipo) VALUES " .
            "('" . $documento . "','" . $numeral . "','" . $indice . "','" . $glosa . "','" . $fecha . "','" . $id_arch . "','" . $gestion . "','" . $id_tipo . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;

    }
    //  * A11. Guardar los archivos del documento
    public static function deleteArchivesByDocument($documento)
    {
        $query = "delete from arch.doc2 where id_doc = '" . $documento . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * A11. Guardar los archivos del documento
    public static function AddHeaderOfDocument($id_tipo, $glosa, $fecha, $id_arch, $gestion)
    {
        //insert into arch.doc( ... ) values ( ... ) RETURNING id_doc
        //$query = "INSERT INTO arch.doc(id_tipo, glosa, fecha, id_arch, gestion) VALUES " .
        //    "('" . $id_tipo . "','" . $glosa . "','" . $fecha . "','" . $id_arch . "','" . $gestion . "') RETURNING id";
        $query = "select * from arch.ff_registrar_documento('" . $id_tipo . "','" . $glosa . "','" . $fecha . "','" . $id_arch . "','" . $gestion . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A12. Obtiene la lista de documentos y contenedores que sean menor al actual y se encuentre libres
    //  * parametros {id: identificador del contenedor raiz }
    public static function GetDataDocumentById($id)
    {
        $query = "select a.id, a.glosa, a.fecha, a.id_doc, a.id_tipo, b.id_raiz, b.id as id_cd from arch.doc a inner join arch.doc_con b on a.id = b.id_rama
        where b.id_raiz = '" . $id . "' and b.tipo_rama = 'A'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function GetDetaFileContainerById($id)
    {
        $query = "select a.id, a.glosa, a.fecha, a.id_doc, a.id_tipo, b.id_raiz, b.id as id_cd from arch.doc a inner join arch.doc_con b on a.id = b.id_rama
        where b.id_raiz = '" . $id . "' and b.tipo_rama <> 'A'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * A13. Obtiene la lista de documentos y contenedores que estan libres para su registro
    public static function GetDocumentFree($id)
    {
        $query = "select d.id, d.glosa, d.fecha, d.id_doc, d.id_tipo from arch.doc d left join arch.doc_con e on d.id = e.id_rama where e.id_rama is null " .
            "and id_tipo = 'A'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function GetContainerFree($id)
    {
        /*
        $query = "select d.id, d.glosa, d.fecha, d.id_doc, d.id_tipo from arch.doc d left join arch.doc_con e on d.id = e.id_raiz where e.id_raiz is null " .
        "and id_tipo <> 'A'";
         */
        $query = "SELECT * FROM arch.ff_contenedor_libre('" . $id . "')";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A14. Guardar los documentos y contenedores en el contenedor
    public static function deleteDocumentsAndContainerFromContainer($container)
    {
        $query = "delete from arch.doc_con where id_raiz = '" . $container . "'";
        $data = collect(DB::select(DB::raw($query)));
        $query = "delete from arch.doc_con where id_raiz = '" . $container . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function AddDocumentsAndContainers($id_rama, $id_tipo_rama, $id_raiz, $id_tipo_raiz, $usuario, $gestion)
    {
        //$query = "SELECT * FROM arch.ff_registrar_detalle_documento('" . $descripcion . "','" . $tipo . "')";
        $query = "INSERT INTO arch.doc_con(id_rama, tipo_rama, id_raiz, tipo_raiz, usr_cre, gestion, orden) VALUES " .
            "('" . $id_rama . "','" . $id_tipo_rama . "','" . $id_raiz . "','" . $id_tipo_raiz . "','" . $usuario . "','" . $gestion . "', 1)";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    // *A15 Obtiene un documento o contenedor o ubiacion por su id
    public static function GetFileContainerById($id)
    {
        $query = "select *, d.id as id_principal from arch.doc d inner join arch.tipos e on d.id_arch = e.id where d.id = " . $id;
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A16. Busca los documentos para la solicitud de prestamo
    public static function getDataDocument($description)
    {
        $query = "select * from arch.ff_buscar_documento('" . $description . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * 17. Obtener una lista de las solicitudes de reserva por usuario.
    public static function GetBookingDocument($user, $year)
    {
        if($user != '')
            $query = "select * from arch.reserva r where r.usr_cre = '" . $user . "' and r.gestion = '" . $year . "'";
        else
            $query = "select * from arch.reserva r where r.gestion = '" . $year . "'";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A18. Guardar la reserva de documentos por el usuario
    public static function AddHeaderOfBookingDocument($ci_per, $des_per, $fecha, $gestion, $usr_cre)
    {
        $query = "SELECT * FROM arch.ff_registrar_reserva('" . $ci_per . "','" . $des_per . "','" . $fecha . "','" . $gestion . "','" . $usr_cre . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    public static function AddStoreBookingDocument($reserva, $id)
    {
        $query = "INSERT INTO arch.res_det(id_res, id_doc) VALUES " .
            "('" . $reserva . "','" . $id . "')";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A19. Busca los documentos reservados para la solicitud
    public static function GetDataBookingDetails($id)
    {
        //$query = "SELECT * FROM arch.res_det d inner join arch.doc2 e on d.id_doc::varchar = e.id_doc where d.id_res = '" . $id . "'";
        
        $query = "select * from arch.ff_documentos_reserva('" . $id . "')";
        //$query = "SELECT e.id_doc, e.fecha, e.glosa, d.id, d.id_doc as id_doc2, d.id_res, d.estado FROM arch.res_det d inner join arch.doc e on d.id_doc = e.id where d.id_res = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A20. Guarda los documentos digitalizados
    public static function StoreDigitalDocument($id, $escaped, $path)
    {
        $query = "INSERT INTO arch.doc_dig(id_doc, des_doc, dig_doc) VALUES (". $id .",'". $path. "', '{$escaped}') ";
        \Log::info($query);
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }


    public static function GetDigitalDocumentById($id, $year){
        $query = "SELECT dig_doc as pdf_data FROM arch.doc_dig d WHERE d.id = ?";
        $data = DB::select($query, [$id]);
        return $data;
    }
    public static function GetDigitalDocumentById2($id, $year){
        $query = "SELECT dig_doc as pdf_data FROM arch.doc_dig d WHERE d.id_doc = ?";
        $data = DB::select($query, [$id]);
        return $data;
    }

    //  * A22. Guarda las tranferencias realizadas entre dos contenedores
    public static function StoreTransferDocumentsAndContainers($id, $id_rama, $id_raiz)
    {
        $query = "UPDATE arch.doc_con set id_raiz = " . $id_raiz . " where id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
    //  * A23. Realizar la entrega, devolucion o cancelacion de la reserva
    public static function StoreChangeStateReservation($id, $documento, $reserva, $estado)
    {

        $query = "UPDATE arch.res_det set estado = '" . $estado . "' where id = '" . $id . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A25. Quita el enlace al contenedor
    public static function RemoveLinkToContainer($id_raiz, $id_rama){
        $query = "DELETE FROM arch.doc_con where id_rama = '" . $id_rama . "' and id_raiz = '" . $id_raiz . "'";
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }

    //  * A26. Obtener una reserva por su id
    public static function GetBookingById($id)
    {
        $query = "select * from arch.reserva r where r.id = " . $id;
        $data = collect(DB::select(DB::raw($query)));
        return $data;
    }
}
