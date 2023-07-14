<?php

namespace App\Http\Controllers;

use App\Archive;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use App\Libraries\JSRClient;


class ArchiveController extends Controller
{
    //  * A1. Obtiene la lista de documentos archivados con una breve descripcion
    //  * parametros {description: descripcion que se esta buscando }
    public function getArchivesByDescription(Request $request)
    {

        $descripcion = strtoupper($request->get('description'));
        $tipo = $request->get('id');
        $data = Archive::GetArchivesByDescription($descripcion, $tipo);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 5;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/users')]
        );
        return json_encode($paginate);
    }

    //  * A2. Obtiene la lista de documentos que pertenecen a un archivo
    //  * parametros {id: identificador del archivo, year: gestion del archivo }
    public function getDocumentsbyArchive(Request $request)
    {
        $archivo = $request->get('id');
        $gestion = $request->get('year');
        $data = Archive::GetDocumentsbyArchive($archivo, $gestion);
        // *A15 Obtiene un documento o contenedor o ubiacion por su id
        $archive = Archive::GetFileContainerById($archivo);
        //return json_encode($data);
        return json_encode(['data' => $data, 'archive' => $archive]);
    }

    //  * A3. Obtiene la lista de tipos de documentos
    //  * parametros {tipo: descripcion del tipo de documento que se necesita }
    public function getTypesDocument(Request $request)
    {
        $tipo = '';
        $data = Archive::GetTypesDocument($tipo);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 5;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getTypesDocument')]
        );
        return json_encode($paginate);
    }

    //  * A9. Obtiene la lista de tipos de documentos que pertenecen a un archivo, contenedor o ubicacion
    //  * parametros {tipo: descripcion del tipo de documento que se necesita }
    public function getTypesDocumentById(Request $request)
    {
        $tipo = $request->get('id_type');
        $data = Archive::GetTypesDocument($tipo);
        return json_encode($data);
    }

    //  * A6. Obtiene la lista de contenedores para archivar con una breve descripcion
    //  * parametros {description: descripcion que se esta buscando }
    public function getFileContainerByDescription(Request $request)
    {

        $descripcion = strtoupper($request->get('description'));
        $data = Archive::getFileContainerByDescription($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 3;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/users')]
        );
        return json_encode($paginate);
    }

    //  * A7. Obtiene la lista de contenedores para archivar con una breve descripcion
    //  * parametros {archivo: datos del documento[descripcion, tipo]}
    public function storeFileContainer(Request $request)
    {
        $contenedor = $request->get('container');
        $gestion = $request->get('year');
        $marcador = $request->get('marker');

        $codigo = ($contenedor['codigo'] ? $contenedor['codigo'] : ''); // condicion if else si existe el dato se asigna lo primero sino lo segundo
        \Log::info($contenedor);

        $tipo = 'C';
        $id_arch = $contenedor['id_arch'];
        $fecha = $contenedor['fecha'];
        $glosa = strtoupper($contenedor['glosa']);
        \Log::info("esta es la glosa " . $glosa);
        if ($glosa == "") {
            $glosa = $contenedor['descr'];
        }
        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'registrar':
                $response = Archive::StoreFileContainer($tipo, $glosa, $fecha, $id_arch, $gestion, $codigo);
                \Log::info($response);
                $container = $response[0]->{'ff_registrar_contenedor'};
                break;
            case 'editar':
                //$data = General::UpdatePerson($personal, $nombres, $paterno, $materno, $sexo, $nacimiento);
                break;
            default:
                break;
        }
        return json_encode($container);
    }

    //  * A8. Obtiene un tipo de archivo en especifico
    public function getTypeArchiveById($id)
    {
        $data = Archive::GetTypeArchiveById($id);
        return json_encode($data);
    }
    //  * A10. Guardar un nuevo tipo de archivo
    public function onStoreTypeArchive(Request $request)
    {
        $archivo = $request->get('archive');
        $marcador = $request->get('marker');
        $descripcion = strtoupper($archivo['descr']);
        switch ($marcador) {
            case 'añadir':
                $tipo = $archivo['type'];
                $data = Archive::OnStoreTypeArchive($descripcion, $tipo);
                break;
            case 'editar':
                $id = $archivo['id'];
                $data = Archive::OnUpdateTypeArchive($id, $descripcion);
                break;
            default:
                break;
        }
        return json_encode($data);
    }

    //  * A11. Guardar los archivos del documento
    public function storeArchivesOfDocument(Request $request)
    {
        $dataArchivesOfDocument = $request->get('archivesOfDocument');
        $gestion = $request->get('year');
        $marcador = $request->get('marker');

        switch ($marcador) {
            case 'añadir':
                # code add...
                $documento = 0;
                foreach ($dataArchivesOfDocument as $item) {
                    //indice: 0, numeral: "", glosa: "", fecha: "", id_tipo: 'A', id_arch: null, descr: "", gestion: ""
                    $indice = $item['indice'];
                    $numeral = strtoupper($item['numeral']);
                    $glosa = strtoupper($item['glosa']);
                    $fecha = $item['fecha'];

                    $id_tipo = $item['id_tipo'];
                    $id_arch = $item['id_arch'];
                    $descripcion = $item['descr'];
                    if ($indice == 1) {
                        $response = Archive::AddHeaderOfDocument($id_tipo, $glosa, $fecha, $id_arch, $gestion);
                        \Log::info($response);
                        $documento = $response[0]->{'ff_registrar_documento'};
                    }
                    $marker = Archive::addArchivesByDocument($documento, $indice, $numeral, $glosa, $fecha, $id_tipo, $id_arch, $gestion);
                }

                break;
            case 'editar':
                # code edit...
                $documento = $request->get('document'); //id del documento

                $marker = Archive::deleteArchivesByDocument($documento);
                foreach ($dataArchivesOfDocument as $item) {
                    //indice: 0, numeral: "", glosa: "", fecha: "", id_tipo: 'A', id_arch: null, descr: "", gestion: ""
                    $indice = $item['indice'];
                    $numeral = strtoupper($item['numeral']);
                    $glosa = strtoupper($item['glosa']);
                    $fecha = $item['fecha'];

                    $id_tipo = $item['id_tipo'];
                    $id_arch = $item['id_arch'];
                    $descripcion = $item['descr'];
                    $marker = Archive::addArchivesByDocument($documento, $indice, $numeral, $glosa, $fecha, $id_tipo, $id_arch, $gestion);
                    $id_tran = 0;
                }
                break;
            default:
                break;

        }
        return json_encode($marker);
    }

    //  * A12. Obtiene la lista de documentos y contenedores que sean menor al actual y se encuentre libres
    public function getDocumentAndFilesContainerById(Request $request)
    {
        $id_fileContainer = $request->get('id');
        \Log::info($id_fileContainer);
        $document = Archive::GetDataDocumentById($id_fileContainer);
        /*
        $data = Archive::GetDocumentAndFilesContainerById($id_fileContainer);
        foreach ($data as $key => $item) {
        # code...
        if(item.tipo_rama = 'A')
        $arrayDOCUMENTOS
        else {
        $arrayCONTENENDORES
        }
        }
         */
        $fileContainer = Archive::GetDetaFileContainerById($id_fileContainer);
        // *A15 Obtiene un documento o contenedor o ubiacion por su id
        $container = Archive::GetFileContainerById($id_fileContainer);
        return json_encode(['documents' => $document, 'fileContainer' => $fileContainer, 'container' => $container]);

    }

    //  * A13. Obtiene la lista de documentos y contenedores que estan libres para su registro
    public function getDocumentAndContainerFree(Request $request)
    {
        $id_fileContainer = $request->get('id');
        \Log::info($id_fileContainer);
        $dataDocuments = Archive::GetDocumentFree($id_fileContainer);
        $dataContainers = Archive::GetContainerFree($id_fileContainer);
        return json_encode(['dataDocuments' => $dataDocuments, 'dataContainers' => $dataContainers]);
    }

    //  * A14. Guardar los documentos y contenedores en el contenedor
    public function storeDocumentsAndContainers(Request $request)
    {
        $documentos = $request->get('documents'); // matrices
        $contenedores = $request->get('fileContainer'); //matrices
        $usuario = $request->get('username'); //dato
        $gestion = $request->get('year'); //dato
        $id_raiz = $request->get('id_container'); //dato
        $id_tipo_raiz = $request->get('id_type'); //dato
        //$contenedor = $request->get('container');//array
        $marcador = $request->get('marker');

        //$id_raiz = $contenedor['id'];//array
        //$id_tipo_raiz = $contenedor['id_tipo'];//dato

        $marker = Archive::deleteDocumentsAndContainerFromContainer($id_raiz);
        foreach ($documentos as $item) {
            \log::info($item);
            $id_rama = $item['id'];
            $id_tipo_rama = strtoupper($item['id_tipo']);
            $marker = Archive::AddDocumentsAndContainers($id_rama, $id_tipo_rama, $id_raiz, $id_tipo_raiz, $usuario, $gestion);
            $id_tran = 0;
        }
        foreach ($contenedores as $item) {
            \log::info($item);
            $id_rama = $item['id'];
            $id_tipo_rama = strtoupper($item['id_tipo']);
            $marker = Archive::AddDocumentsAndContainers($id_rama, $id_tipo_rama, $id_raiz, $id_tipo_raiz, $usuario, $gestion);
            $id_tran = 0;
        }
        return json_encode($marker);
    }
    //  * A16. Busca los documentos para la solicitud de prestamo
    public function getDataDocument(Request $request)
    {
        $descripcion = strtoupper($request->get('description')); // '' cadena vacia
        $data = Archive::GetDataDocument($descripcion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 15;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getDebtorsDocument')]
        );
        return json_encode($paginate);
    }
    //  * 17. Obtener una lista de las solicitudes de reserva por usuario.
    public function getBookingDocument(Request $request)
    {
        $usuario = $request->get('user');
        $gestion = $request->get('year');
        $data = Archive::GetBookingDocument($usuario, $gestion);
        $page = ($request->get('page') ? $request->get('page') : 1);
        $perPage = 15;
        $paginate = new LengthAwarePaginator(
            $data->forPage($page, $perPage),
            $data->count(),
            $perPage,
            $page,
            ['path' => url('api/getDebtorsDocument')]
        );
        return json_encode($paginate);
    }
    //  * A18. Guardar la reserva de documentos por el usuario
    public function storeBookingDocument(Request $request)
    {
        $usuario = $request->get('user');
        $ci_per = $usuario['nodip'];
        $des_per = $usuario['descripcion'];
        $gestion = $usuario['gestion'];
        $usr_cre = $usuario['usuario'];

        $reservas = $request->get('reservations');
        $fecha = date('d-m-Y');
        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'registrar':
                # code add...
                $response = Archive::AddHeaderOfBookingDocument($ci_per, $des_per, $fecha, $gestion, $usr_cre);
                $reserva = $response[0]->{'ff_registrar_reserva'};
                foreach ($reservas as $item) {
                    $id_doc = $item['id'];
                    $marker = Archive::AddStoreBookingDocument($reserva, $id_doc);
                }
                break;
            case 'editar':
                break;
            default:
                break;

        }
        return json_encode($marker);
    }
    //  * A19. Busca los documentos reservados para la solicitud
    public function getDataBookingDetails(Request $request)
    {
        $id_booking = $request->get('id');
        $bookingDetail = Archive::GetDataBookingDetails($id_booking);
        $booking = Archive::GetBookingById($id_booking);
        return json_encode(['bookingDetail' => $bookingDetail, 'booking' => $booking]);

        return json_encode($data);
    }

    //  * A20. Guarda los documentos digitalizados
    public function storeDigitalDocument(Request $request)
    {
        /*
        $fileNameExt = $request->file('productimage')->getClientOriginalName();
        $fileName = pathinfo($fileNameExt, PATHINFO_FILENAME);
        $fileNameToStore = $fileName.'_'.time().'.'.$fileExt;
        $pathToStore = $request->file('productimage')->storeAs('public/images',$fileNameToStore);
        */
        $id_document = $request->get('id_doc');
        $year = $request->get('gestion');
        $fileExt = $request->file('file')->getClientOriginalExtension();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            //path
            $path = "documento";
            $file_name = 'documento-'. $year . '-' . strval($id_document).'.'. $fileExt;//$file->getClientOriginalName();
            $ruta = $path . "/" . $file_name;
            $file->storeAs($path, $file_name);
            //$file->store('documento');    
            //documento digital
            $data = file_get_contents($file);
            // Escapar el dato binario
            $escaped = pg_escape_bytea($data);
            // Insertarlo en la base de datos
            $data = Archive::StoreDigitalDocument($id_document, $escaped, $ruta);

        } else {
            return response()->json(['error' => 'File not exist!']);
        }
        return response()->json(['success' => 'Uploaded Successfully.']);
    }
    //  * A21. Obtiene el documento digital seleccionado
    public function getDigitalDocumentById(Request $request)
    {
        $id = $request->get('id');
        $year = $request->get('year');
        $result = Archive::GetDigitalDocumentById2($id, $year);
        //$path = Archive::GetDigitalDocumentById($id, $year);

        if (!empty($result[0]->pdf_data)) {
            //$my_bytea = stream_get_contents($result[0]->pdf_data);
            $my_bytea = stream_get_contents($result[0]->pdf_data);
            //$my_string = pg_unescape_bytea($my_bytea);
            //$html_data = htmlspecialchars($my_string);
            \Log::info($my_bytea);
            return $my_bytea;
        } else {
            return response()->json([
                'error' => 'No se encontró ningún registro con el ID proporcionado.',
            ]);
        }

        /*
        \Log::info($path[0]->des_doc);
        $contenidoArchivo = storage_path('app/' . $path[0]->des_doc);
        \Log::info($contenidoArchivo);
        if (Storage::exists($path[0]->des_doc)) {
            // Obtener el contenido del archivo
            $contenidoArchivo = Storage::get($path[0]->des_doc);
            //\Log::info( $contenidoArchivo);
            // Devolver el archivo como respuesta
            return $contenidoArchivo;
            //return response($contenidoArchivo, 200, $headers);
        }else{
            return response()->json([
                'error' => 'No se encontró ningún registro con el ID proporcionado.',
            ]);
        }*/
    }

    
    //  * A22. Guarda las tranferencias realizadas entre dos contenedores
    public function storeTransferDocumentsAndContainers(Request $request)
    {
        $todos = $request->get('all');
        $marcador = $request->get('marker');
        switch ($marcador) {
            case 'editar':
                # code add...
                foreach ($todos as $item) {
                    $id = $item['id_cd'];
                    $id_rama = $item['id'];
                    $id_raiz = $item['id_raiz'];
                    $marker = Archive::StoreTransferDocumentsAndContainers($id, $id_rama, $id_raiz);
                }
                break;
            default:
                break;
        }
        return json_encode($marker);
    }
    //  * A23. Realizar la entrega, devolucion o cancelacion de la reserva
    public function storeChangeStateReservation(Request $request)
    {
        $estado = $request->get('state'); // matrices
        $documentos = $request->get('selected'); //matrices
        //$marcador = $request->get('marker');    
        foreach ($documentos as $item) {
            $id = $item['id'];
            $documento = $item['id_doc2'];
            $reserva = $item['id_res'];
            $marker = Archive::StoreChangeStateReservation($id, $documento, $reserva, $estado);
            $id_tran = 0;
        }
        return json_encode($marker);
    }

    //  * A24. Muestra el reporte del documento
    public function getReportDocument(Request $request)
    {
        $p_id = $request->get('id');
        $nreport = 'ArchiveDetailDocumentLetter';
        $controls = array(
            'p_id' => $p_id,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }
    
    //  * A25. Guardar un nuevo tipo de archivo
    public function removeLinkToContainer(Request $request)
    {
        $id_rama = $request->get('id_rama');
        $id_raiz = $request->get('id_raiz');
        $marcador = $request->get('marker');
        \Log::info("DATOS");
        \Log::info($id_rama);
        \Log::info($id_raiz);
        switch ($marcador) {
            case 'añadir':
                break;
            case 'editar':
                $data = Archive::RemoveLinkToContainer($id_raiz, $id_rama);
                break;
            default:
                break;
        }
        return json_encode($data);
    }
    //  * A26. Muestra el reporte del documento
    public function getReportBooking(Request $request)
    {
        $p_id = $request->get('id');
        \Log::info($p_id);
        $nreport = 'BookingDetailDocumentLetter';
        $controls = array(
            'p_id' => $p_id,
        );
        $report = JSRClient::GetReportWithParameters($nreport, $controls);
        return $report;
    }
}
